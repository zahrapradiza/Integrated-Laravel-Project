@extends('layouts.app', ['title' => $user->isCustomer() ? 'Buat Pesanan Boneka' : 'Form Penjualan Kasir (Stock-Out)'])

@section('content')
<div class="max-w-5xl mx-auto py-4 space-y-6">
    <div class="mb-4">
        <a href="{{ route('sales.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-600 inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat Transaksi
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    {{ $user->isCustomer() ? 'Pemesanan Boneka Baru' : 'Transaksi Penjualan Kasir (Stock-Out)' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $user->isCustomer() ? 'Pilih boneka yang ingin Anda pesan. Stok fisik akan dikurangi secara otomatis setelah transaksi berhasil disimpan.' : 'Catat transaksi penjualan pelanggan toko dengan validasi stok atomik di server.' }}
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <i class="fa-solid fa-boxes-packing mr-1.5"></i> Stock-Out Otomatis
            </span>
        </div>

        <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-6">
            @csrf

            <!-- Customer & Transaction Information -->
            <div class="bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-4">
                @if($user->isCustomer())
                    <!-- Fixed Customer Info for Customer Role -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-xl border border-slate-200">
                        <div class="flex items-center space-x-3">
                            <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Pemesan (Customer)</span>
                                <h3 class="text-base font-extrabold text-slate-900">{{ $user->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $user->email }} &bull; {{ $user->phone ?: 'No. telp belum diisi' }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            Pesanan Akun Pribadi
                        </span>
                    </div>
                @else
                    <!-- Admin / Staff Customer Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="sm:col-span-6">
                            <label for="customer_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pelanggan Terdaftar (Member)
                            </label>
                            <select name="customer_id" id="customer_id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium">
                                <option value="">-- Bukan Member / Pembeli Umum --</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }} ({{ $cust->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Pilih jika pembeli memiliki akun member customer.</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="customer_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Pembeli Umum (Guest)
                            </label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name') }}"
                                placeholder="Contoh: Ibu Linda / Pelanggan Walk-In"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                            <p class="text-[11px] text-slate-400 mt-1">Diisi bila pembeli adalah pembeli umum tanpa akun member.</p>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan Transaksi (Opsional)
                    </label>
                    <input type="text" name="notes" id="notes"
                        value="{{ old('notes') }}"
                        placeholder="Contoh: Kado ulang tahun, dibungkus pita kado, atau pengiriman kurir..."
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 flex items-center">
                            <i class="fa-solid fa-cart-shopping text-emerald-600 mr-2"></i> Keranjang Boneka yang Dibeli (Multi-Item)
                        </h2>
                        <p class="text-xs text-slate-500">Pilih boneka dan masukkan kuantitas. Sistem akan memvalidasi ketersediaan stok fisik secara ketat.</p>
                    </div>

                    <button type="button" id="addRowBtn" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition-colors">
                        <i class="fa-solid fa-plus mr-1.5"></i> Tambah Baris Boneka
                    </button>
                </div>

                @error('items')
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-bold flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <!-- Dynamic Items Table -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm text-slate-600" id="itemsTable">
                            <thead class="bg-slate-50 text-[11px] uppercase font-extrabold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 w-6/12 sm:w-5/12">Pilih Boneka</th>
                                    <th class="px-3 py-3 w-2/12 text-center">Stok Tersedia</th>
                                    <th class="px-3 py-3 w-2/12 text-center">Jumlah Beli</th>
                                    <th class="px-3 py-3 w-2/12 text-right">Harga Jual (Rp)</th>
                                    <th class="px-3 py-3 w-2/12 text-right">Subtotal (Rp)</th>
                                    <th class="px-2 py-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTbody" class="divide-y divide-slate-100">
                                <!-- Dynamic rows inserted by JavaScript -->
                            </tbody>
                            <tfoot class="bg-slate-50 font-black text-slate-900 border-t border-slate-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-3.5 text-right uppercase tracking-wider text-xs">
                                        Total Tagihan Belanja:
                                    </td>
                                    <td class="px-3 py-3.5 text-right text-base text-emerald-700 font-black" id="grandTotalDisplay">
                                        Rp 0
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('sales.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-sm shadow-md transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Proses Transaksi & Kurangi Stok</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const productsData = @json($products);
    const oldItems = @json(old('items', []));

    let rowIndex = 0;

    function createRow(item = null) {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/60 transition-colors item-row';
        tr.dataset.index = rowIndex;

        const selectedProductId = item ? item.product_id : '';
        const quantity = item ? item.quantity : 1;

        let optionsHtml = '<option value="">-- Pilih Boneka --</option>';
        productsData.forEach(p => {
            const isSelected = selectedProductId == p.id ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.stock}" data-sku="${p.sku}" data-size="${p.size || ''}" ${isSelected}>
                ${p.name} [${p.sku}] - ${p.size || ''} (Stok: ${p.stock})
            </option>`;
        });

        tr.innerHTML = `
            <td class="px-4 py-3">
                <select name="items[${rowIndex}][product_id]" required class="product-select w-full px-3 py-2 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                    ${optionsHtml}
                </select>
            </td>
            <td class="px-3 py-3 text-center text-xs font-bold text-slate-700 stock-cell">
                -
            </td>
            <td class="px-3 py-3 text-center">
                <input type="number" name="items[${rowIndex}][quantity]" value="${quantity}" min="1" required
                    class="qty-input w-24 text-center px-2 py-1.5 rounded-xl border border-slate-300 text-xs sm:text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <span class="stock-warning text-[10px] text-rose-600 font-bold block mt-0.5 hidden">Stok Kurang!</span>
            </td>
            <td class="px-3 py-3 text-right font-semibold text-slate-800 price-display">
                Rp 0
            </td>
            <td class="px-3 py-3 text-right font-black text-slate-900 subtotal-display">
                Rp 0
            </td>
            <td class="px-2 py-3 text-center">
                <button type="button" class="remove-row-btn p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Baris">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </td>
        `;

        document.getElementById('itemsTbody').appendChild(tr);

        const select = tr.querySelector('.product-select');
        const qtyInput = tr.querySelector('.qty-input');
        const removeBtn = tr.querySelector('.remove-row-btn');

        select.addEventListener('change', () => {
            const selectedOpt = select.options[select.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const stock = parseInt(selectedOpt.getAttribute('data-stock')) || 0;
                const price = parseFloat(selectedOpt.getAttribute('data-price')) || 0;
                tr.querySelector('.stock-cell').textContent = `${stock} pcs`;
                tr.querySelector('.price-display').textContent = formatRupiah(price);
                qtyInput.max = stock;
            } else {
                tr.querySelector('.stock-cell').textContent = '-';
                tr.querySelector('.price-display').textContent = 'Rp 0';
            }
            validateAndCalculateRow(tr);
        });

        qtyInput.addEventListener('input', () => validateAndCalculateRow(tr));

        removeBtn.addEventListener('click', () => {
            const allRows = document.querySelectorAll('.item-row');
            if (allRows.length <= 1) {
                alert('Minimal harus ada 1 baris boneka dalam pesanan.');
                return;
            }
            tr.remove();
            calculateGrandTotal();
        });

        if (selectedProductId) {
            select.dispatchEvent(new Event('change'));
        }

        validateAndCalculateRow(tr);
        rowIndex++;
    }

    function validateAndCalculateRow(tr) {
        const select = tr.querySelector('.product-select');
        const selectedOpt = select.options[select.selectedIndex];
        const stock = selectedOpt && selectedOpt.value ? (parseInt(selectedOpt.getAttribute('data-stock')) || 0) : 0;
        const price = selectedOpt && selectedOpt.value ? (parseFloat(selectedOpt.getAttribute('data-price')) || 0) : 0;
        const qty = parseInt(tr.querySelector('.qty-input').value) || 0;
        const warning = tr.querySelector('.stock-warning');

        if (selectedOpt && selectedOpt.value && qty > stock) {
            warning.classList.remove('hidden');
            tr.querySelector('.qty-input').classList.add('border-rose-500', 'bg-rose-50');
        } else {
            warning.classList.add('hidden');
            tr.querySelector('.qty-input').classList.remove('border-rose-500', 'bg-rose-50');
        }

        const subtotal = qty * price;
        tr.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(tr => {
            const select = tr.querySelector('.product-select');
            const selectedOpt = select.options[select.selectedIndex];
            const price = selectedOpt && selectedOpt.value ? (parseFloat(selectedOpt.getAttribute('data-price')) || 0) : 0;
            const qty = parseInt(tr.querySelector('.qty-input').value) || 0;
            grandTotal += (qty * price);
        });
        document.getElementById('grandTotalDisplay').textContent = formatRupiah(grandTotal);
    }

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(number));
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (oldItems && oldItems.length > 0) {
            oldItems.forEach(item => createRow(item));
        } else {
            createRow();
        }

        document.getElementById('addRowBtn').addEventListener('click', () => {
            createRow();
        });
    });
</script>
@endsection
