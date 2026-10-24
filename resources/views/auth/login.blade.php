@php
    $activePortal = old('portal_role', $portal ?? request('portal', 'customer'));
    if (!in_array($activePortal, ['customer', 'staff', 'admin'])) {
        $activePortal = 'customer';
    }
@endphp

@extends('layouts.app', ['title' => 'Portal Masuk - BonekaKu'])

@section('content')
<div class="max-w-xl mx-auto py-8 sm:py-12 px-4">

    <!-- Role Portal Selector Tabs -->
    <div class="bg-white p-1.5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between mb-6 gap-1" id="portalTabs">
        <!-- Customer Tab -->
        <a href="{{ route('login', ['portal' => 'customer']) }}"
           data-portal="customer"
           class="portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 {{ $activePortal === 'customer' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/25' : 'text-slate-600 hover:text-rose-600 hover:bg-rose-50/50' }}">
            <i class="fa-solid fa-heart {{ $activePortal === 'customer' ? 'text-white' : 'text-rose-400' }}"></i>
            <span>Pelanggan</span>
        </a>

        <!-- Staff Tab -->
        <a href="{{ route('login', ['portal' => 'staff']) }}"
           data-portal="staff"
           class="portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 {{ $activePortal === 'staff' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/25' : 'text-slate-600 hover:text-amber-600 hover:bg-amber-50/50' }}">
            <i class="fa-solid fa-user-gear {{ $activePortal === 'staff' ? 'text-white' : 'text-amber-500' }}"></i>
            <span>Staff Toko</span>
        </a>

        <!-- Admin Tab -->
        <a href="{{ route('login', ['portal' => 'admin']) }}"
           data-portal="admin"
           class="portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 {{ $activePortal === 'admin' ? 'bg-slate-900 text-white shadow-md shadow-slate-900/25' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/50' }}">
            <i class="fa-solid fa-shield-halved {{ $activePortal === 'admin' ? 'text-indigo-300' : 'text-indigo-500' }}"></i>
            <span>Administrator</span>
        </a>
    </div>

    <!-- Main Login Card Container -->
    <div id="loginCard" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden transition-all duration-300">

        <!-- Role-Specific Card Header -->
        <div id="headerBanner" class="p-6 sm:p-8 border-b border-slate-100 transition-colors duration-300
            @if($activePortal === 'customer') bg-gradient-to-br from-rose-50/70 via-pink-50/30 to-white
            @elseif($activePortal === 'staff') bg-gradient-to-br from-amber-50/70 via-orange-50/30 to-white
            @else bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white
            @endif">

            <div class="flex items-center justify-between mb-4">
                <span id="badgeRole" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                    @if($activePortal === 'customer') bg-rose-100 text-rose-700 border border-rose-200
                    @elseif($activePortal === 'staff') bg-amber-100 text-amber-800 border border-amber-300
                    @else bg-indigo-500/20 text-indigo-300 border border-indigo-400/30
                    @endif">
                    <i id="badgeIcon" class="mr-1.5
                        @if($activePortal === 'customer') fa-solid fa-heart
                        @elseif($activePortal === 'staff') fa-solid fa-user-gear
                        @else fa-solid fa-shield-halved
                        @endif"></i>
                    <span id="badgeText">
                        @if($activePortal === 'customer') Portal Pelanggan
                        @elseif($activePortal === 'staff') Portal Staff Kasir & Gudang
                        @else Portal Administrator & Owner
                        @endif
                    </span>
                </span>

                <span class="text-xs font-mono {{ $activePortal === 'admin' ? 'text-indigo-300/80' : 'text-slate-400' }}">
                    BonekaKu Auth
                </span>
            </div>

            <!-- Title & Subtitle -->
            <h1 id="portalTitle" class="text-2xl sm:text-3xl font-black tracking-tight {{ $activePortal === 'admin' ? 'text-white' : 'text-slate-900' }}">
                @if($activePortal === 'customer')
                    Masuk Pelanggan BonekaKu
                @elseif($activePortal === 'staff')
                    Portal Staff Operasional
                @else
                    Portal Administrator & Owner
                @endif
            </h1>

            <p id="portalSubtitle" class="text-xs sm:text-sm mt-1.5 {{ $activePortal === 'admin' ? 'text-slate-300' : 'text-slate-500' }}">
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

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alamat Email Akun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                            Kata Sandi (Password) <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            placeholder="Masukkan password akun Anda"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-slate-900 focus:ring-slate-900 border-slate-300">
                        <span class="ml-2 text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="submitBtn"
                        class="w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2
                        @if($activePortal === 'customer') bg-rose-600 hover:bg-rose-700 active:bg-rose-800 shadow-rose-500/20
                        @elseif($activePortal === 'staff') bg-amber-600 hover:bg-amber-700 active:bg-amber-800 shadow-amber-500/20
                        @else bg-slate-900 hover:bg-slate-800 active:bg-black shadow-slate-900/25
                        @endif">
                        <i id="btnIcon" class="
                            @if($activePortal === 'customer') fa-solid fa-arrow-right-to-bracket
                            @elseif($activePortal === 'staff') fa-solid fa-cash-register
                            @else fa-solid fa-shield-halved
                            @endif"></i>
                        <span id="btnText">
                            @if($activePortal === 'customer') Masuk Akun Pelanggan
                            @elseif($activePortal === 'staff') Masuk Portal Staff
                            @else Masuk Panel Administrator
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Role-Specific Bottom Notice / Registration Link -->
                <div id="footerNotice" class="pt-4 border-t border-slate-100">
                    @if($activePortal === 'customer')
                        <!-- Customer Mode: Show Register Link -->
                        <div id="customerNotice" class="text-center text-xs sm:text-sm text-slate-500">
                            Belum punya akun pembeli?
                            <a href="{{ route('register') }}" class="font-extrabold text-rose-600 hover:text-rose-700 hover:underline">
                                Daftar Akun Customer Sekarang
                            </a>
                        </div>
                    @elseif($activePortal === 'staff')
                        <!-- Staff Mode: Hide Register Link -->
                        <div id="staffNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-info text-amber-500 text-sm"></i>
                            <span>Akses internal petugas kasir & gudang. Pendaftaran staf dikelola langsung oleh Administrator.</span>
                        </div>
                    @else
                        <!-- Admin Mode: Hide Register Link -->
                        <div id="adminNotice" class="text-center text-xs text-slate-500 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-lock text-indigo-500 text-sm"></i>
                            <span>Akses terproteksi tingkat tinggi khusus Pemilik Toko & Administrator sistem.</span>
                        </div>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- Security & Testing Notice -->
    <div class="mt-6 text-center text-xs text-slate-400">
        <p>Aplikasi Toko BonekaKu &bull; Sistem Inventori & Penjualan Terintegrasi</p>
        <p class="mt-1 text-[11px] text-slate-400/80">Kredensial akun demo pengujian dosen tertera pada dokumentasi <code class="text-slate-600 font-mono">README.md</code>.</p>
    </div>

</div>

<!-- Client-side Interactive Tab Switching -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const portalConfigs = {
            customer: {
                title: 'Masuk Pelanggan BonekaKu',
                subtitle: 'Belanja boneka favoritmu dan pantau pesanan dengan mudah.',
                badgeText: 'Portal Pelanggan',
                badgeIcon: 'fa-solid fa-heart',
                badgeClass: 'bg-rose-100 text-rose-700 border border-rose-200',
                bannerClass: 'p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-br from-rose-50/70 via-pink-50/30 to-white text-slate-900',
                titleClass: 'text-2xl sm:text-3xl font-black tracking-tight text-slate-900',
                subtitleClass: 'text-xs sm:text-sm mt-1.5 text-slate-500',
                btnText: 'Masuk Akun Pelanggan',
                btnIcon: 'fa-solid fa-arrow-right-to-bracket',
                btnClass: 'w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 shadow-rose-500/20'
            },
            staff: {
                title: 'Portal Staff Operasional',
                subtitle: 'Masuk khusus petugas kasir penjualan (Stock-Out) dan penerimaan stok gudang (Stock-In).',
                badgeText: 'Portal Staff Kasir & Gudang',
                badgeIcon: 'fa-solid fa-user-gear',
                badgeClass: 'bg-amber-100 text-amber-800 border border-amber-300',
                bannerClass: 'p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-br from-amber-50/70 via-orange-50/30 to-white text-slate-900',
                titleClass: 'text-2xl sm:text-3xl font-black tracking-tight text-slate-900',
                subtitleClass: 'text-xs sm:text-sm mt-1.5 text-slate-500',
                btnText: 'Masuk Portal Staff',
                btnIcon: 'fa-solid fa-cash-register',
                btnClass: 'w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 shadow-amber-500/20'
            },
            admin: {
                title: 'Portal Administrator & Owner',
                subtitle: 'Akses manajemen master data, laporan keuangan, dan otorisasi sistem.',
                badgeText: 'Portal Administrator & Owner',
                badgeIcon: 'fa-solid fa-shield-halved',
                badgeClass: 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30',
                bannerClass: 'p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white',
                titleClass: 'text-2xl sm:text-3xl font-black tracking-tight text-white',
                subtitleClass: 'text-xs sm:text-sm mt-1.5 text-slate-300',
                btnText: 'Masuk Panel Administrator',
                btnIcon: 'fa-solid fa-shield-halved',
                btnClass: 'w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 bg-slate-900 hover:bg-slate-800 active:bg-black shadow-slate-900/25'
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

        const customerNotice = document.getElementById('customerNotice');
        const staffNotice = document.getElementById('staffNotice');
        const adminNotice = document.getElementById('adminNotice');

        function switchPortal(role) {
            if (!portalConfigs[role]) return;
            const cfg = portalConfigs[role];

            // Update Hidden Input
            portalRoleInput.value = role;

            // Update URL query string without reloading
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.set('portal', role);
                window.history.replaceState(null, '', url.toString());
            }

            // Update Tab Buttons UI
            tabButtons.forEach(btn => {
                const bRole = btn.getAttribute('data-portal');
                if (bRole === role) {
                    if (role === 'customer') {
                        btn.className = 'portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 bg-rose-500 text-white shadow-md shadow-rose-500/25';
                    } else if (role === 'staff') {
                        btn.className = 'portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 bg-amber-500 text-white shadow-md shadow-amber-500/25';
                    } else {
                        btn.className = 'portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 bg-slate-900 text-white shadow-md shadow-slate-900/25';
                    }
                } else {
                    btn.className = 'portal-tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-extrabold text-center transition-all flex items-center justify-center space-x-1.5 text-slate-600 hover:bg-slate-100/60';
                }
            });

            // Update Header & Content
            headerBanner.className = cfg.bannerClass;
            badgeRole.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider ' + cfg.badgeClass;
            badgeIcon.className = cfg.badgeIcon + ' mr-1.5';
            badgeText.textContent = cfg.badgeText;
            portalTitle.textContent = cfg.title;
            portalTitle.className = cfg.titleClass;
            portalSubtitle.textContent = cfg.subtitle;
            portalSubtitle.className = cfg.subtitleClass;

            // Update Submit Button
            submitBtn.className = cfg.btnClass;
            btnIcon.className = cfg.btnIcon;
            btnText.textContent = cfg.btnText;

            // Update Bottom Notice
            customerNotice.classList.toggle('hidden', role !== 'customer');
            customerNotice.classList.toggle('block', role === 'customer');

            staffNotice.classList.toggle('hidden', role !== 'staff');
            staffNotice.classList.toggle('block', role === 'staff');

            adminNotice.classList.toggle('hidden', role !== 'admin');
            adminNotice.classList.toggle('block', role === 'admin');
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
