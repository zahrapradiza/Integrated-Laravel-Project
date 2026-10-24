@extends('layouts.app', ['title' => 'Katalog Boneka & Plushie'])

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-rose-500 via-plush-500 to-pink-500 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 text-white/10 text-9xl pointer-events-none">
            <i class="fa-solid fa-store"></i>
        </div>
        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur border border-white/30 text-white mb-2 inline-block">
                🧸 Koleksi Lengkap Toko BonekaKu
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Katalog Boneka & Plushie</h1>
            <p class="text-white/90 text-sm mt-1">
                Pilih boneka favorit Anda dari berbagai kategori seperti Teddy Bear, Karakter Hewan, Anime Plushie, Buket Wisuda, hingga Bantal Boneka empuk.
            </p>
        </div>
    </div>

    <!-- Search & Category Filter -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form action="{{ route('products.catalog') }}" method="GET" class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama boneka, karakter, atau ukuran..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-plush-500 focus:border-plush-500 bg-slate-50 focus:bg-white transition-all">
            </div>

            <div class="sm:col-span-4 flex items-center space-x-2">
                <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-plush-500 focus:border-plush-500 bg-slate-50 focus:bg-white transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-plush-600 hover:bg-plush-700 text-white font-bold text-xs shadow-sm transition-colors">
                    Filter
                </button>
            </div>
        </form>

        <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">
            Ditemukan: <strong>{{ $products->total() }}</strong> Boneka
        </span>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($products as $item)
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Top Category & SKU -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase bg-plush-50 text-plush-700 border border-plush-200">
                            {{ $item->category->name ?? 'Boneka' }}
                        </span>
                        <span class="text-xs font-bold text-slate-400 font-mono">{{ $item->sku }}</span>
                    </div>

                    <!-- Doll Name -->
                    <h2 class="font-extrabold text-slate-900 text-base group-hover:text-plush-600 transition-colors line-clamp-2">
                        {{ $item->name }}
                    </h2>

                    <!-- Specs -->
                    <div class="mt-3 space-y-1 text-xs text-slate-600 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Ukuran:</span>
                            <span class="font-bold text-slate-800">{{ $item->size ?: '-' }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-1">
                            <span class="text-slate-400">Bahan:</span>
                            <span class="font-medium text-slate-700 text-right line-clamp-1" title="{{ $item->material }}">{{ $item->material ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Pricing & Stock -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Harga Jual</span>
                        <span class="text-lg font-black text-rose-600">
                            Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($item->stock > 0)
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Tersedia: {{ $item->stock }} pcs
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            Habis
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-3xl border border-slate-200 text-center text-slate-400">
                <i class="fa-solid fa-basket-shopping text-5xl mb-3 text-slate-300"></i>
                <p class="font-bold text-base text-slate-700">Tidak ada boneka yang sesuai dengan filter pencarian.</p>
                <p class="text-xs text-slate-400 mt-1">Silakan coba pilih kategori lain atau bersihkan kata kunci.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bar -->
    @if($products->hasPages())
        <div class="bg-white p-4 rounded-2xl border border-slate-200">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
