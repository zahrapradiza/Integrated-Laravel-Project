<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the role-specific dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalProducts = Product::count();
            $lowStockProducts = Product::with('category')
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock', 'asc')
                ->get();
            $totalLowStock = $lowStockProducts->count();
            $totalSuppliers = Supplier::count();
            $totalCategories = Category::count();
            $totalSales = Sale::where('status', 'completed')->sum('total');
            $totalPurchases = Purchase::where('status', 'completed')->sum('total');
            $totalUsers = User::count();

            $recentSales = Sale::with(['user', 'customer', 'items'])
                ->latest('sold_at')
                ->take(5)
                ->get();

            $recentPurchases = Purchase::with(['supplier', 'user', 'items'])
                ->latest('purchased_at')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'user',
                'totalProducts',
                'totalLowStock',
                'lowStockProducts',
                'totalSuppliers',
                'totalCategories',
                'totalSales',
                'totalPurchases',
                'totalUsers',
                'recentSales',
                'recentPurchases'
            ));
        }

        if ($user->isStaff()) {
            $totalProducts = Product::count();
            $lowStockProducts = Product::with('category')
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock', 'asc')
                ->get();
            $totalLowStock = $lowStockProducts->count();
            $totalSuppliers = Supplier::count();
            $totalCategories = Category::count();

            $recentSales = Sale::with(['user', 'customer', 'items'])
                ->latest('sold_at')
                ->take(5)
                ->get();

            $recentPurchases = Purchase::with(['supplier', 'user', 'items'])
                ->latest('purchased_at')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'user',
                'totalProducts',
                'totalLowStock',
                'lowStockProducts',
                'totalSuppliers',
                'totalCategories',
                'recentSales',
                'recentPurchases'
            ));
        }

        // Customer Dashboard
        $myOrdersCount = Sale::where('customer_id', $user->id)->count();
        $myTotalSpent = Sale::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->sum('total');

        $myRecentOrders = Sale::with(['items.product'])
            ->where('customer_id', $user->id)
            ->latest('sold_at')
            ->take(5)
            ->get();

        $featuredProducts = Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'user',
            'myOrdersCount',
            'myTotalSpent',
            'myRecentOrders',
            'featuredProducts'
        ));
    }
}
