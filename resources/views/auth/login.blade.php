@extends('layouts.app', ['title' => 'Masuk ke Sistem BonekaKu'])

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Login Form Card -->
        <div class="lg:col-span-7 bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-100">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-plush-100 text-plush-600 mb-4 shadow-sm">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali!</h1>
                <p class="text-slate-500 text-sm mt-1">Silakan masukkan email dan password untuk masuk ke akun Anda.</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email') }}"
                            placeholder="nama@bonekaku.test"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-plush-500 focus:border-plush-500 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-bold text-slate-700">
                            Kata Sandi (Password) <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-plush-500 focus:border-plush-500 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-plush-600 focus:ring-plush-500 border-slate-300">
                        <span class="ml-2 text-slate-600 font-medium">Ingat Saya di Perangkat Ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-plush-600 hover:bg-plush-700 active:bg-plush-800 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Masuk ke Sistem</span>
                    </button>
                </div>

                <!-- Register Customer Link -->
                <div class="pt-4 border-t border-slate-100 text-center text-sm text-slate-500">
                    Belum punya akun pembeli?
                    <a href="{{ route('register') }}" class="font-bold text-plush-600 hover:text-plush-700 hover:underline">
                        Daftar Akun Customer Sekarang
                    </a>
                </div>
            </form>
        </div>

        <!-- Demo Accounts Card for Quick Lecturer Testing -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-3xl shadow-xl relative overflow-hidden border border-slate-700">
                <div class="absolute -right-6 -bottom-6 text-slate-800/40 text-8xl pointer-events-none">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center space-x-2 text-amber-400 mb-2">
                        <i class="fa-solid fa-circle-info text-base"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Info Pengujian Dosen / Demo</span>
                    </div>
                    <h2 class="text-lg font-bold text-white mb-2">Akun Demo Siap Pakai</h2>
                    <p class="text-slate-300 text-xs mb-4">
                        Klik tombol <strong>Gunakan</strong> pada masing-masing role di bawah untuk mengisi form login secara otomatis:
                    </p>

                    <div class="space-y-3">
                        <!-- Admin Account -->
                        <div class="p-3 rounded-2xl bg-slate-800/80 border border-purple-500/30 flex items-center justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-purple-500/20 text-purple-300 border border-purple-500/40">ADMIN</span>
                                <p class="text-sm font-semibold text-white mt-1">admin@bonekaku.test</p>
                                <p class="text-xs text-slate-400">Password: <code class="text-amber-300">password123</code></p>
                            </div>
                            <button type="button" onclick="fillLogin('admin@bonekaku.test', 'password123')" class="px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition-colors">
                                Gunakan
                            </button>
                        </div>

                        <!-- Staff Account -->
                        <div class="p-3 rounded-2xl bg-slate-800/80 border border-amber-500/30 flex items-center justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/40">STAFF</span>
                                <p class="text-sm font-semibold text-white mt-1">staff@bonekaku.test</p>
                                <p class="text-xs text-slate-400">Password: <code class="text-amber-300">password123</code></p>
                            </div>
                            <button type="button" onclick="fillLogin('staff@bonekaku.test', 'password123')" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold transition-colors">
                                Gunakan
                            </button>
                        </div>

                        <!-- Customer Account -->
                        <div class="p-3 rounded-2xl bg-slate-800/80 border border-rose-500/30 flex items-center justify-between">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/40">CUSTOMER</span>
                                <p class="text-sm font-semibold text-white mt-1">customer@bonekaku.test</p>
                                <p class="text-xs text-slate-400">Password: <code class="text-amber-300">password123</code></p>
                            </div>
                            <button type="button" onclick="fillLogin('customer@bonekaku.test', 'password123')" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-colors">
                                Gunakan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Summary Note -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="font-bold text-slate-800 flex items-center space-x-1.5">
                    <i class="fa-solid fa-shield-halved text-plush-600"></i>
                    <span>Hak Akses Terproteksi Server-Side:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-slate-500 pl-1">
                    <li><strong class="text-slate-700">Admin:</strong> Kelola User/Role, CRUD Produk/Supplier/Kategori, Batal Transaksi, Laporan Penuh.</li>
                    <li><strong class="text-slate-700">Staff:</strong> Read Produk/Supplier/Kategori, Transaksi Jual/Beli (Tanpa hak Batal/User).</li>
                    <li><strong class="text-slate-700">Customer:</strong> Akses Katalog Boneka, Riwayat Belanja Saya.</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
