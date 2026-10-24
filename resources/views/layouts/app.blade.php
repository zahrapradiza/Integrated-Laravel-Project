<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'BonekaKu Inventory & Sales') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        plush: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex flex-col min-h-full text-slate-800 bg-slate-50">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-plush-600 font-extrabold text-xl tracking-tight group">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-plush-100 text-plush-600 group-hover:scale-105 transition-transform duration-200 shadow-sm">
                            <i class="fa-solid fa-paw text-xl"></i>
                        </span>
                        <span>Boneka<span class="text-slate-900">Ku</span></span>
                    </a>
                    <span class="hidden md:inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        UTS Web Programming 2
                    </span>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-plush-50 text-plush-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-chart-pie mr-1.5 text-xs"></i> Dashboard
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <!-- Admin & Staff Navigation -->
                            <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('products.*') ? 'bg-plush-50 text-plush-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-boxes-stacked mr-1.5 text-xs"></i> Produk Boneka
                            </a>
                            <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('categories.*') ? 'bg-plush-50 text-plush-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-tags mr-1.5 text-xs"></i> Kategori
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-industry mr-1.5 text-xs"></i> Pemasok
                            </a>
                            <a href="{{ route('purchases.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('purchases.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-truck-ramp-box mr-1.5 text-xs"></i> Stock-In (Pembelian)
                            </a>
                            <a href="{{ route('sales.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('sales.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-cash-register mr-1.5 text-xs"></i> Stock-Out (Penjualan)
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('users.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    <i class="fa-solid fa-users-gear mr-1.5 text-xs"></i> Kelola User
                                </a>
                            @endif
                        @else
                            <!-- Customer Navigation -->
                            <a href="{{ route('products.catalog') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('products.catalog') ? 'bg-plush-50 text-plush-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-store mr-1.5 text-xs"></i> Katalog Boneka
                            </a>
                            <a href="{{ route('sales.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('sales.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-bag-shopping mr-1.5 text-xs"></i> Pesanan Saya
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- User Profile & Action -->
                <div class="flex items-center space-x-3">
                    @auth
                        <!-- Role Badge -->
                        @if(auth()->user()->isAdmin())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300 shadow-sm">
                                <i class="fa-solid fa-crown mr-1.5 text-purple-600"></i> ADMIN
                            </span>
                        @elseif(auth()->user()->isStaff())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-sm">
                                <i class="fa-solid fa-user-gear mr-1.5 text-amber-600"></i> STAFF
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300 shadow-sm">
                                <i class="fa-solid fa-heart mr-1.5 text-rose-500"></i> CUSTOMER
                            </span>
                        @endif

                        <!-- User Info -->
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-slate-500">{{ auth()->user()->email }}</span>
                        </div>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Logout" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-600 border border-slate-200 transition-colors" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-xl bg-plush-600 text-white hover:bg-plush-700 shadow-sm transition-colors">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-1.5"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center px-4 py-2 text-sm font-bold rounded-xl bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition-colors">
                            Daftar Customer
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 flex items-start p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm animate-fade-in" role="alert">
                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 mt-0.5">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-emerald-900">Operasi Berhasil</p>
                    <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-start p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm" role="alert">
                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mr-3 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-rose-900">Peringatan / Error</p>
                    <p class="text-sm text-rose-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm" role="alert">
                <div class="flex items-center mb-2">
                    <i class="fa-solid fa-circle-xmark text-amber-600 mr-2"></i>
                    <p class="font-bold">Terdapat beberapa kesalahan input data:</p>
                </div>
                <ul class="list-disc list-inside text-sm text-amber-800 space-y-1 ml-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Yield View Content -->
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <div class="flex items-center space-x-2">
                <span class="font-extrabold text-plush-600">BonekaKu</span>
                <span>&bull;</span>
                <span>Sistem Manajemen Inventori & Penjualan Toko Boneka</span>
            </div>
            <div>
                <span>Tugas UTS Web Programming 2 &copy; {{ date('Y') }}</span>
            </div>
        </div>
    </footer>

</body>
</html>
