@php
    $activePortal = old('portal_role', $portal ?? request('portal', 'customer'));
    if (!in_array($activePortal, ['customer', 'staff', 'admin'])) {
        $activePortal = 'customer';
    }
@endphp

@extends('layouts.app', ['title' => "Portal Masuk - Diza's Plushie"])

@section('content')
<div class="max-w-xl mx-auto py-6 sm:py-10 px-4">

    <!-- Role Portal Selector Tabs -->
    <div class="bg-white/90 backdrop-blur p-1.5 rounded-3xl border border-pink-100 shadow-sm flex items-center justify-between mb-6 gap-1.5" id="portalTabs">
        <!-- Customer Tab -->
        <a href="{{ route('login', ['portal' => 'customer']) }}"
           data-portal="customer"
           class="portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 {{ $activePortal === 'customer' ? 'bg-gradient-to-r from-pink-400 to-rose-400 text-white shadow-md shadow-pink-300/40' : 'text-slate-600 hover:text-rose-500 hover:bg-pink-50/50' }}">
            <i class="fa-solid fa-heart {{ $activePortal === 'customer' ? 'text-white' : 'text-rose-400' }}"></i>
            <span>Pelanggan</span>
        </a>

        <!-- Staff Tab -->
        <a href="{{ route('login', ['portal' => 'staff']) }}"
           data-portal="staff"
           class="portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 {{ $activePortal === 'staff' ? 'bg-gradient-to-r from-emerald-400 to-teal-400 text-white shadow-md shadow-teal-300/40' : 'text-slate-600 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
            <i class="fa-solid fa-user-gear {{ $activePortal === 'staff' ? 'text-white' : 'text-emerald-500' }}"></i>
            <span>Staff Toko</span>
        </a>

        <!-- Admin Tab -->
        <a href="{{ route('login', ['portal' => 'admin']) }}"
           data-portal="admin"
           class="portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 {{ $activePortal === 'admin' ? 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white shadow-md shadow-purple-300/40' : 'text-slate-600 hover:text-purple-600 hover:bg-purple-50/50' }}">
            <i class="fa-solid fa-shield-halved {{ $activePortal === 'admin' ? 'text-white' : 'text-purple-500' }}"></i>
            <span>Administrator</span>
        </a>
    </div>

    <!-- Main Login Card Container -->
    <div id="loginCard" class="bg-white rounded-3xl border border-pink-100 shadow-xl shadow-pink-100/40 overflow-hidden transition-all duration-300">

        <!-- Role-Specific Card Header -->
        <div id="headerBanner" class="p-6 sm:p-8 border-b border-pink-50 transition-colors duration-300
            @if($activePortal === 'customer') bg-gradient-to-br from-pink-50 via-rose-50/40 to-white text-slate-800
            @elseif($activePortal === 'staff') bg-gradient-to-br from-emerald-50 via-teal-50/40 to-white text-slate-800
            @else bg-gradient-to-br from-purple-50 via-indigo-50/40 to-white text-slate-800
            @endif">

            <div class="flex items-center justify-between mb-4">
                <span id="badgeRole" class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider
                    @if($activePortal === 'customer') bg-pink-100 text-rose-600 border border-pink-200
                    @elseif($activePortal === 'staff') bg-emerald-100 text-emerald-800 border border-emerald-200
                    @else bg-purple-100 text-purple-800 border border-purple-200
                    @endif">
                    <i id="badgeIcon" class="mr-1.5
                        @if($activePortal === 'customer') fa-solid fa-sparkles
                        @elseif($activePortal === 'staff') fa-solid fa-user-gear
                        @else fa-solid fa-shield-halved
                        @endif"></i>
                    <span id="badgeText">
                        @if($activePortal === 'customer') Masuk Cepat Tanpa Password 🌸
                        @elseif($activePortal === 'staff') Portal Staff Kasir & Gudang 🌿
                        @else Portal Pemilik & Administrator 👑
                        @endif
                    </span>
                </span>

                <span class="text-xs font-bold text-slate-400">
                    Diza's Plushie Auth
                </span>
            </div>

            <!-- Title & Subtitle -->
            <h1 id="portalTitle" class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                @if($activePortal === 'customer')
                    {{ "Masuk Pelanggan Diza's Plushie" }}
                @elseif($activePortal === 'staff')
                    Portal Staff Operasional
                @else
                    Portal Administrator & Owner
                @endif
            </h1>

            <p id="portalSubtitle" class="text-xs sm:text-sm mt-1.5 text-slate-500 font-medium">
                @if($activePortal === 'customer')
                    Belanja boneka favoritmu dan pantau pesanan dengan mudah.
                @elseif($activePortal === 'staff')
                    Masuk khusus petugas kasir penjualan (Stock-Out) dan penerimaan stok gudang (Stock-In).
                @else
                    Akses manajemen master data, laporan keuangan, dan otorisasi sistem.
                @endif
            </p>
        </div>

        <!-- Login Form Body -->
        <div class="p-6 sm:p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-5" id="loginForm">
                @csrf

                <!-- Hidden Portal Role Input -->
                <input type="hidden" name="portal_role" id="portal_role" value="{{ $activePortal }}">

                <!-- Customer Only: Name Input (Optional for fast personalization) -->
                <div id="nameFieldGroup" class="{{ $activePortal === 'customer' ? 'block' : 'hidden' }}">
                    <label for="name" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Panggilan / Lengkap <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-rose-300">
                            <i class="fa-solid fa-user-tag"></i>
                        </span>
                        <input type="text" name="name" id="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Nadia Cantik"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-pink-200 text-sm transition-all bg-pink-50/30 hover:bg-white focus:bg-white focus:ring-2 focus:ring-rose-400 focus:border-rose-400 font-medium">
                    </div>
                </div>

                <!-- Email Input (Required for all portals) -->
                <div>
                    <label for="email" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                        Alamat Email Akun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-pink-200 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-rose-400 focus:border-rose-400 font-medium">
                    </div>
                </div>

                <!-- Password Input (Only for Staff & Admin) -->
                <div id="passwordFieldGroup" class="{{ $activePortal === 'customer' ? 'hidden' : 'block' }}">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-black uppercase tracking-wider text-slate-700">
                            Kata Sandi (Password) <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" id="password"
                            placeholder="Masukkan kata sandi akun Anda"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-pink-200 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-rose-400 focus:border-rose-400 font-medium">
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-rose-500 focus:ring-rose-400 border-pink-200">
                        <span class="ml-2 text-slate-600 font-bold">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="w-full py-3.5 px-4 rounded-2xl font-black text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2
                        @if($activePortal === 'customer') bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 shadow-pink-300/30
                        @elseif($activePortal === 'staff') bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-teal-300/30
                        @else bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 shadow-purple-300/30
                        @endif">
                        <i id="btnIcon" class="
                            @if($activePortal === 'customer') fa-solid fa-heart
                            @elseif($activePortal === 'staff') fa-solid fa-cash-register
                            @else fa-solid fa-shield-halved
                            @endif"></i>
                        <span id="btnText">
                            @if($activePortal === 'customer') Masuk Cepat Tanpa Password 🌸
                            @elseif($activePortal === 'staff') Masuk Portal Staff 🌿
                            @else Masuk Panel Administrator 👑
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Role-Specific Bottom Notice / Registration Link -->
                <div id="footerNotice" class="pt-4 border-t border-pink-50">
                    @if($activePortal === 'customer')
                        <div id="customerNotice" class="text-center text-xs sm:text-sm text-slate-500 font-semibold">
                            Masuk otomatis tanpa password! Atau ingin isi profil lengkap?
                            <a href="{{ route('register') }}" class="font-black text-rose-500 hover:text-rose-600 hover:underline ml-1">
                                Daftar Akun Customer Sekarang
                            </a>
                        </div>
                    @elseif($activePortal === 'staff')
                        <div id="staffNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-info text-emerald-500 text-sm"></i>
                            <span>Akses internal staf kasir & gudang. Akun resmi didaftarkan oleh Administrator.</span>
                        </div>
                    @else
                        <div id="adminNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2 font-medium">
                            <i class="fa-solid fa-lock text-purple-500 text-sm"></i>
                            <span>Akses terbatas Pemilik & Administrator Diza's Plushie dengan otorisasi server-side.</span>
                        </div>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- Security & Testing Notice -->
    <div class="mt-6 text-center text-xs text-slate-400 font-medium">
        <p>🌸 Diza's Plushie Store &bull; Sistem Inventori & Kasir Boneka Cantik</p>
        <p class="mt-1 text-[11px] text-slate-400/80">Kredensial akun demo untuk penilaian dosen tertera rapi pada file <code class="text-slate-600 font-mono">README.md</code>.</p>
    </div>

</div>

<!-- Client-side Interactive Tab Switching -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const portalConfigs = {
            customer: {
                title: "Masuk Pelanggan Diza's Plushie",
                subtitle: 'Belanja boneka favoritmu dan pantau pesanan dengan mudah.',
                badgeText: 'Masuk Cepat Tanpa Password 🌸',
                badgeIcon: 'fa-solid fa-sparkles',
                badgeClass: 'bg-pink-100 text-rose-600 border border-pink-200',
                bannerClass: 'p-6 sm:p-8 border-b border-pink-50 bg-gradient-to-br from-pink-50 via-rose-50/40 to-white text-slate-800',
                btnText: 'Masuk Cepat Tanpa Password 🌸',
                btnIcon: 'fa-solid fa-heart',
                btnClass: 'w-full py-3.5 px-4 rounded-2xl font-black text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 shadow-pink-300/30'
            },
            staff: {
                title: 'Portal Staff Operasional',
                subtitle: 'Masuk khusus petugas kasir penjualan (Stock-Out) dan penerimaan stok gudang (Stock-In).',
                badgeText: 'Portal Petugas Kasir & Gudang 🌿',
                badgeIcon: 'fa-solid fa-user-gear',
                badgeClass: 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                bannerClass: 'p-6 sm:p-8 border-b border-pink-50 bg-gradient-to-br from-emerald-50 via-teal-50/40 to-white text-slate-800',
                btnText: 'Masuk Portal Staff 🌿',
                btnIcon: 'fa-solid fa-cash-register',
                btnClass: 'w-full py-3.5 px-4 rounded-2xl font-black text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-teal-300/30'
            },
            admin: {
                title: 'Portal Administrator & Owner',
                subtitle: 'Akses manajemen master data, laporan keuangan, dan otorisasi sistem.',
                badgeText: 'Portal Pemilik & Administrator 👑',
                badgeIcon: 'fa-solid fa-shield-halved',
                badgeClass: 'bg-purple-100 text-purple-800 border border-purple-200',
                bannerClass: 'p-6 sm:p-8 border-b border-pink-50 bg-gradient-to-br from-purple-50 via-indigo-50/40 to-white text-slate-800',
                btnText: 'Masuk Panel Administrator 👑',
                btnIcon: 'fa-solid fa-shield-halved',
                btnClass: 'w-full py-3.5 px-4 rounded-2xl font-black text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 shadow-purple-300/30'
            }
        };

        const tabButtons = document.querySelectorAll('.portal-tab-btn');
        const portalRoleInput = document.getElementById('portal_role');
        const headerBanner = document.getElementById('headerBanner');
        const badgeRole = document.getElementById('badgeRole');
        const badgeIcon = document.getElementById('badgeIcon');
        const badgeText = document.getElementById('badgeText');
        const portalTitle = document.getElementById('portalTitle');
        const portalSubtitle = document.getElementById('portalSubtitle');
        const submitBtn = document.getElementById('submitBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');

        const nameFieldGroup = document.getElementById('nameFieldGroup');
        const passwordFieldGroup = document.getElementById('passwordFieldGroup');
        const passwordInput = document.getElementById('password');

        const customerNotice = document.getElementById('customerNotice');
        const staffNotice = document.getElementById('staffNotice');
        const adminNotice = document.getElementById('adminNotice');

        function switchPortal(role) {
            if (!portalConfigs[role]) return;
            const cfg = portalConfigs[role];

            // Update Hidden Input
            portalRoleInput.value = role;

            // Update URL query string
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.set('portal', role);
                window.history.replaceState(null, '', url.toString());
            }

            // Update Tab Button Styles
            tabButtons.forEach(btn => {
                const bRole = btn.getAttribute('data-portal');
                if (bRole === role) {
                    if (role === 'customer') {
                        btn.className = 'portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-400 to-rose-400 text-white shadow-md shadow-pink-300/40';
                    } else if (role === 'staff') {
                        btn.className = 'portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 bg-gradient-to-r from-emerald-400 to-teal-400 text-white shadow-md shadow-teal-300/40';
                    } else {
                        btn.className = 'portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white shadow-md shadow-purple-300/40';
                    }
                } else {
                    btn.className = 'portal-tab-btn flex-1 py-3 px-3 rounded-2xl text-xs sm:text-sm font-black text-center transition-all flex items-center justify-center space-x-2 text-slate-600 hover:text-pink-500 hover:bg-pink-50/50';
                }
            });

            // Update Header & Badge
            headerBanner.className = cfg.bannerClass;
            badgeRole.className = 'inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider ' + cfg.badgeClass;
            badgeIcon.className = cfg.badgeIcon + ' mr-1.5';
            badgeText.textContent = cfg.badgeText;
            portalTitle.textContent = cfg.title;
            portalSubtitle.textContent = cfg.subtitle;

            // Form Inputs Visibility (Passwordless for Customer)
            if (role === 'customer') {
                nameFieldGroup.classList.remove('hidden');
                passwordFieldGroup.classList.add('hidden');
                passwordInput.removeAttribute('required');
            } else {
                nameFieldGroup.classList.add('hidden');
                passwordFieldGroup.classList.remove('hidden');
                passwordInput.setAttribute('required', 'required');
            }

            // Submit Button
            submitBtn.className = cfg.btnClass;
            btnIcon.className = cfg.btnIcon;
            btnText.textContent = cfg.btnText;

            // Notice
            const footerNotice = document.getElementById('footerNotice');
            if (footerNotice) {
                if (role === 'customer') {
                    footerNotice.innerHTML = `
                        <div id="customerNotice" class="text-center text-xs sm:text-sm text-slate-500 font-semibold">
                            Masuk otomatis tanpa password! Atau ingin isi profil lengkap?
                            <a href="{{ route('register') }}" class="font-black text-rose-500 hover:text-rose-600 hover:underline ml-1">
                                ${'Daftar Akun'} ${'Customer Sekarang'}
                            </a>
                        </div>`;
                } else if (role === 'staff') {
                    footerNotice.innerHTML = `
                        <div id="staffNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2 font-medium">
                            <i class="fa-solid fa-circle-info text-emerald-500 text-sm"></i>
                            <span>Akses internal staf kasir & gudang. Akun resmi didaftarkan oleh Administrator.</span>
                        </div>`;
                } else {
                    footerNotice.innerHTML = `
                        <div id="adminNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2 font-medium">
                            <i class="fa-solid fa-lock text-purple-500 text-sm"></i>
                            <span>Akses terbatas Pemilik & Administrator Diza's Plushie dengan otorisasi server-side.</span>
                        </div>`;
                }
            }
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const selectedRole = this.getAttribute('data-portal');
                switchPortal(selectedRole);
            });
        });
    });
</script>
@endsection
