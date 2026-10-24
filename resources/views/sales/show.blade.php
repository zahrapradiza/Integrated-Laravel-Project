@extends('layouts.app', ['title' => 'Faktur Penjualan ' . $sale->sale_number])

@section('content')
<div class="max-w-4xl mx-auto py-4 space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('sales.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-600 inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat Penjualan
        </a>

        <!-- Cancel Transaction Button (Admin Only & Completed Only) -->
        @if(auth()->user()->isAdmin() && $sale->status === 'completed')
            <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin membatalkan transaksi penjualan {{ $sale->sale_number }}? Seluruh kuantitas boneka pada nota ini akan dikembalikan ke stok inventori toko secara otomatis.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs shadow-sm transition-colors">
                    <i class="fa-solid fa-ban mr-1.5"></i> Batalkan Penjualan Ini
                </button>
            </form>
        @endif
    </div>

    <!-- Store Invoice Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6">

        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
                <div class="flex items-center space-x-2 mb-1.5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-receipt"></i>
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Struk Resmi Pembelian Boneka</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tracking-tight">{{ $sale->sale_number }}</h1>
                <p class="text-xs text-slate-400 mt-1">Waktu Transaksi: {{ \Carbon\Carbon::parse($sale->sold_at)->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            <!-- Status Badge -->
            <div class="text-left sm:text-right">
                @if($sale->status === 'completed')
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <i class="fa-solid fa-circle-check mr-1.5 text-sm"></i> LUNAS / COMPLETED
                    </span>
                    <p class="text-[11px] text-slate-400 mt-1">Stok telah terpotong dari gudang</p>
                @else
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-black bg-rose-100 text-rose-700 border border-rose-200">
                        <i class="fa-solid fa-ban mr-1.5 text-sm"></i> DIBATALKAN / CANCELLED
                    </span>
                    <p class="text-[11px] text-rose-500 font-semibold mt-1">Stok telah dikembalikan ke inventori</p>
                @endif
            </div>
        </div>

        <!-- Customer & Cashier Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <!-- Customer Info -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Identitas Pembeli (Customer)</span>
                <p class="text-sm font-extrabold text-slate-900">
                    {{ $sale->customer_name ?? ($sale->customer->name ?? 'Pelanggan Umum') }}
                </p>
                @if($sale->customer)
                    <p class="text-slate-600"><strong class="text-slate-700">Akun Member:</strong> {{ $sale->customer->email }}</p>
                    <p class="text-slate-600"><strong class="text-slate-700">No. Kontak:</strong> {{ $sale->customer->phone ?: '-' }}</p>
                    <p class="text-slate-500 text-[11px]"><strong class="text-slate-700">Alamat Kirim:</strong> {{ $sale->customer->address ?: '-' }}</p>
                @else
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">
                        Pelanggan Umum (Non-Member)
                    </span>
                @endif
            </div>

            <!-- Cashier & Note Info -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kasir / Pembuat Transaksi</span>
                <p class="text-sm font-extrabold text-slate-900">{{ $sale->user->name ?? '-' }} ({{ strtoupper($sale->user->role ?? '-') }})</p>
                <p class="text-slate-600"><strong class="text-slate-700">Email Kasir:</strong> {{ $sale->user->email ?? '-' }}</p>
                <div class="pt-1 border-t border-slate-200/60 mt-1">
                    <span class="text-slate-400 block text-[10px]">Catatan Nota:</span>
                    <p class="text-slate-700 italic">{{ $sale->notes ?: 'Tidak ada catatan.' }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Boneka yang Dibeli</h2>
            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase font-extrabold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 w-12 text-center">No</th>
                            <th class="px-5 py-3">Produk Boneka</th>
                            <th class="px-5 py-3">Kategori & Ukuran</th>
                            <th class="px-5 py-3 text-center">Kuantitas</th>
                            <th class="px-5 py-3 text-right">Harga Satuan</th>
                            <th class="px-5 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sale->items as $index => $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-extrabold text-slate-900">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $item->product->sku ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-xs">
                                    <span class="font-semibold text-slate-700">{{ $item->product->category->name ?? '-' }}</span>
                                    <div class="text-slate-400 text-[11px]">{{ $item->product->size ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-center font-black text-emerald-600">
                                    {{ $item->quantity }} pcs
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium text-slate-700">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-black text-slate-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 font-black text-slate-900 border-t border-slate-200">
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-right text-xs uppercase tracking-wider text-slate-500">
                                Total Kuantitas:
                            </td>
                            <td class="px-5 py-3 text-center text-sm font-black text-emerald-700">
                                {{ $sale->items->sum('quantity') }} pcs
                            </td>
                            <td class="px-5 py-3 text-right text-xs uppercase tracking-wider text-slate-500">
                                Total Tagihan:
                            </td>
                            <td class="px-5 py-3 text-right text-base font-black text-emerald-700">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
