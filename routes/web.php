<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Inventori & Penjualan Toko Boneka (BonekaKu)
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes (All Roles)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Role-Specific Protected Route Groups
    |--------------------------------------------------------------------------
    */

    // 1. ADMIN ONLY (User Management & Sensitive Actions)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () {
            return redirect()->route('dashboard')->with('success', 'Akses Admin Terverifikasi.');
        })->name('users.index');
    });

    // 2. ADMIN & STAFF (Inventory Stock-In & Stock-Out Management)
    Route::middleware('role:admin,staff')->prefix('manage')->name('manage.')->group(function () {
        Route::get('/inventory', function () {
            return redirect()->route('dashboard')->with('success', 'Akses Operasional Staff & Admin Terverifikasi.');
        })->name('inventory.index');
    });

    // 3. CUSTOMER ONLY (Personal Orders & Shopping Experience)
    Route::middleware('role:customer')->prefix('shop')->name('customer.')->group(function () {
        Route::get('/my-orders', function () {
            return redirect()->route('dashboard')->with('success', 'Akses Pesanan Customer Terverifikasi.');
        })->name('orders.index');
    });
});
