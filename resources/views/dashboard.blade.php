@extends('layouts.app', ['title' => 'Dashboard Utama'])

@section('content')
<div class="space-y-8 py-2">

    <!-- Top Greeting Banner -->
    <div class="bg-gradient-to-r from-plush-600 via-rose-500 to-pink-500 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-plush-500/10 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 text-white/10 text-9xl pointer-events-none">
            <i class="fa-solid fa-paw"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-white/20 backdrop-blur border border-white/30 text-white mb-2">
                    @if($user->isAdmin())
                        <i class="fa-solid fa-shield-halved mr-1.5"></i> ROLE: ADMINISTRATOR / OWNER
                    @elseif($user->isStaff())
                        <i class="fa-solid fa-user-gear mr-1.5"></i> ROLE: STAFF KASIR & INVENTORI
                    @else
                        <i class="fa-solid fa-heart mr-1.5"></i> ROLE: PELANGGAN (CUSTOMER)
                    @endif
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Halo, {{ $user->name }}! 👋
                </h1>
                <p class="text-white/90 text-sm mt-1 max-w-2xl">
                    @if($user->isAdmin())
                        Anda memiliki hak akses penuh untuk mengelola master data produk, membatalkan transaksi, mengontrol stok, serta memantau omzet toko boneka.
                    @elseif($user->isStaff())
                        Kelola alur transaksi Stock-In dari supplier, transaksi penjualan harian pelanggan, dan pantau stok boneka secara real-time.
                    @else
                        Temukan beragam boneka lucu, teddy bear klasik, dan plushie impor berkualitas premium untuk kado maupun koleksi terbaik Anda.
                    @endif
                </p>
            </div>

            <!-- Role Quick Badges -->
            <div class="flex items-center space-x-2">
                <span class="px-3.5 py-2 rounded-2xl bg-white/15 backdrop-blur text-xs font-bold border border-white/20">
                    <i class="fa-regular fa-clock mr-1"></i> {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- 1. ADMIN DASHBOARD VIEW -->
    <!-- ===================================================================== -->
    @if($user->isAdmin())
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Sales -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Penjualan (Omzet)</span>
                    <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center">
                        <i class="fa-solid fa-arrow-trend-up mr-1"></i> Transaksi Penjualan Selesai
                    </p>
                </div>
            </div>

            <!-- Total Purchases -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pembelian (Stock-In)</span>
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-cart-flatbed text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</p>
                    <p class="text-xs text-blue-600 font-semibold mt-1 flex items-center">
                        <i class="fa-solid fa-boxes-packing mr-1"></i> Pengadaan dari Supplier
                    </p>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Produk Boneka</span>
                    <span class="w-10 h-10 rounded-xl bg-plush-100 text-plush-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-900">{{ $totalProducts }} <span class="text-sm font-semibold text-slate-500">Item</span></p>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Dalam {{ $totalCategories }} Kategori Boneka</p>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white p-5 rounded-2xl border {{ $totalLowStock > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Peringatan Low Stock</span>
                    <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-rose-600">{{ $totalLowStock }} <span class="text-sm font-semibold text-rose-400">Produk</span></p>
                    <p class="text-xs text-rose-600 font-semibold mt-1">Stok &le; Batas Minimum Toko</p>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Table Section (Crucial For Lecturer Testing) -->
        @if($lowStockProducts->count() > 0)
            <div class="bg-white rounded-3xl border border-rose-200 shadow-sm overflow-hidden">
                <div class="p-5 bg-rose-50/60 border-b border-rose-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center text-sm shadow-sm">
                            <i class="fa-solid fa-bell"></i>
                        </span>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Perhatian: Produk Mendekati / Habis Stok (Low Stock)</h2>
                            <p class="text-xs text-rose-600 font-medium">Produk dengan sisa stok di bawah atau sama dengan batas minimum (5 unit). Segera lakukan Stock-In.</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-700 border border-rose-300">
                        {{ $lowStockProducts->count() }} Perlu Restock
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-extrabold text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3.5">SKU & Produk</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5 text-center">Sisa Stok</th>
                                <th class="px-6 py-3.5 text-center">Batas Minimum</th>
                                <th class="px-6 py-3.5 text-right">Harga Beli</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lowStockProducts as $prod)
                                <tr class="hover:bg-rose-50/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-extrabold text-slate-900">{{ $prod->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $prod->sku }} &bull; {{ $prod->size ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ $prod->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-700 border border-rose-300">
                                            {{ $prod->stock }} pcs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-500">
                                        {{ $prod->minimum_stock }} pcs
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-slate-700">
                                        Rp {{ number_format($prod->purchase_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-extrabold bg-rose-500 text-white">
                                            <i class="fa-solid fa-triangle-exclamation mr-1 text-[9px]"></i> Kritis
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Transactions Grid (Sales & Purchases) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Sales -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center">
                        <i class="fa-solid fa-receipt text-emerald-500 mr-2"></i> Penjualan Terakhir (Stock-Out)
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentSales as $sale)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $sale->sale_number }}</p>
                                <p class="text-xs text-slate-500">
                                    Pembeli: <span class="font-semibold text-slate-700">{{ $sale->customer_name ?? ($sale->customer->name ?? 'Pelanggan Umum') }}</span> &bull;
                                    {{ \Carbon\Carbon::parse($sale->sold_at)->format('d M H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-emerald-600">Rp {{ number_format($sale->total, 0, ',', '.') }}</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $sale->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-slate-400">Belum ada transaksi penjualan.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Purchases -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center">
                        <i class="fa-solid fa-truck text-blue-500 mr-2"></i> Pembelian Terakhir (Stock-In)
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentPurchases as $purchase)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $purchase->purchase_number }}</p>
                                <p class="text-xs text-slate-500">
                                    Supplier: <span class="font-semibold text-slate-700">{{ $purchase->supplier->name ?? '-' }}</span> &bull;
                                    {{ \Carbon\Carbon::parse($purchase->purchased_at)->format('d M H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-blue-600">Rp {{ number_format($purchase->total, 0, ',', '.') }}</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $purchase->status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $purchase->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-slate-400">Belum ada transaksi pembelian.</div>
                    @endforelse
                </div>
            </div>
        </div>

    <!-- ===================================================================== -->
    <!-- 2. STAFF DASHBOARD VIEW -->
    <!-- ===================================================================== -->
    @elseif($user->isStaff())
        <!-- Staff Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Produk di Gudang</span>
                    <span class="w-10 h-10 rounded-xl bg-plush-100 text-plush-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-900">{{ $totalProducts }} <span class="text-sm font-semibold text-slate-500">Item</span></p>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Data master siap ditransaksikan</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border {{ $totalLowStock > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200' }} shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Peringatan Low Stock</span>
                    <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-rose-600">{{ $totalLowStock }} <span class="text-sm font-semibold text-rose-400">Produk</span></p>
                    <p class="text-xs text-rose-600 font-semibold mt-1">Perlu segera diajukan Stock-In</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pemasok Terdaftar</span>
                    <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                        <i class="fa-solid fa-industry text-lg"></i>
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-2xl font-black text-slate-900">{{ $totalSuppliers }} <span class="text-sm font-semibold text-slate-500">Pabrik/Supplier</span></p>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Mitra pengadaan boneka</p>
                </div>
            </div>
        </div>

        <!-- Staff Low Stock Alert Table -->
        @if($lowStockProducts->count() > 0)
            <div class="bg-white rounded-3xl border border-rose-200 shadow-sm overflow-hidden">
                <div class="p-5 bg-rose-50/60 border-b border-rose-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center text-sm shadow-sm">
                            <i class="fa-solid fa-bell"></i>
                        </span>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Perhatian: Produk Rendah Stok (Staff Alert)</h2>
                            <p class="text-xs text-rose-600 font-medium">Laporkan atau lakukan pembelian (Stock-In) dari pemasok resmi.</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-extrabold text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3.5">SKU & Nama Produk</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5 text-center">Sisa Stok</th>
                                <th class="px-6 py-3.5 text-center">Min. Stok</th>
                                <th class="px-6 py-3.5 text-right">Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lowStockProducts as $prod)
                                <tr class="hover:bg-rose-50/40 transition-colors">
                                    <td class="px-6 py-4 font-extrabold text-slate-900">
                                        {{ $prod->name }}
                                        <div class="text-xs text-slate-400 font-mono">{{ $prod->sku }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $prod->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center font-black text-rose-600">{{ $prod->stock }} pcs</td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $prod->minimum_stock }} pcs</td>
                                    <td class="px-6 py-4 text-right font-black text-slate-800">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    <!-- ===================================================================== -->
    <!-- 3. CUSTOMER DASHBOARD VIEW -->
    <!-- ===================================================================== -->
    @else
        <!-- Customer Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl shadow-sm">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pesanan Saya</span>
                    <p class="text-2xl font-black text-slate-900 mt-0.5">{{ $myOrdersCount }} Pesanan</p>
                    <p class="text-xs text-rose-600 font-medium">Total belanja terkonfirmasi</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-sm">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran Belanja</span>
                    <p class="text-2xl font-black text-emerald-600 mt-0.5">Rp {{ number_format($myTotalSpent, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 font-medium">Akumulasi transaksi sukses</p>
                </div>
            </div>
        </div>

        <!-- Customer Recent Orders -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-rose-500"></i> Riwayat Pesanan Saya
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar boneka yang pernah Anda beli di Toko BonekaKu.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($myRecentOrders as $order)
                    <div class="p-5 hover:bg-slate-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="font-extrabold text-slate-900">{{ $order->sale_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-emerald-100 text-emerald-700">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">
                                <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($order->sold_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>

                        <!-- Item Badges -->
                        <div class="flex flex-wrap gap-2 text-xs">
                            @foreach($order->items as $it)
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 font-medium border border-slate-200">
                                    {{ $it->product->name ?? 'Boneka' }} &times; <strong>{{ $it->quantity }}</strong>
                                </span>
                            @endforeach
                        </div>

                        <div class="mt-3 text-right">
                            <span class="text-xs text-slate-400">Total Tagihan:</span>
                            <span class="text-base font-black text-rose-600 ml-1">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <i class="fa-solid fa-basket-shopping text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm font-semibold">Anda belum memiliki riwayat pesanan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Featured Products Preview for Customer -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center">
                        <i class="fa-solid fa-heart mr-2 text-rose-500"></i> Koleksi Boneka Terpopuler
                    </h2>
                    <p class="text-xs text-slate-500">Pilihan boneka empuk berstandar kualitas terbaik.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($featuredProducts as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-plush-100 text-plush-700 font-mono">
                                    {{ $item->category->name ?? 'Boneka' }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 font-mono">{{ $item->sku }}</span>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base line-clamp-1">{{ $item->name }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Ukuran: <strong class="text-slate-700">{{ $item->size ?? '-' }}</strong></p>
                            <p class="text-xs text-slate-400 line-clamp-1">Bahan: {{ $item->material ?? '-' }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Harga</span>
                                <span class="text-base font-black text-rose-600">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                Stok: {{ $item->stock }} pcs
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
