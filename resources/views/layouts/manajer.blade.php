@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RBPL Hotel - Manajer Hotel</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        hotel: {
                            bg: '#FAF9F6',
                            dark: '#1A1A1A',
                            gold: '#D4AF37',
                            goldLight: '#C5A880',
                            text: '#2D2D2D'
                        }
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
            font-family:'Montserrat',sans-serif;
            background:#FAF9F6;
            color:#2D2D2D;
        }

        .sidebar-active{
            background:#2D2D2D;
            border-left:4px solid #D4AF37;
            color:#D4AF37 !important;
        }
    </style>
</head>

<body class="overflow-x-hidden">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-hotel-dark hidden md:flex flex-col justify-between border-r border-stone-800 text-stone-300">

        <div>

            <!-- LOGO -->
            <div class="p-6 flex flex-col items-center border-b border-stone-800">

                <img
                    src="{{ asset('logo_hotel.png') }}"
                    alt="RBPL Hotel"
                    class="w-28 h-28 object-contain mb-4"
                >

                <span class="text-xs font-bold tracking-[0.2em] text-hotel-gold">
                    RBPL HOTEL
                </span>

                <span class="text-[10px] text-stone-500 uppercase tracking-wider mt-1">
                    Management System
                </span>

            </div>

            <!-- MENU -->
            <nav class="mt-6 px-4 space-y-1">

                <p class="text-[10px] uppercase tracking-widest text-stone-500 px-3 mb-3 font-semibold">
                    Menu Utama
                </p>

                <a href="{{ route('dashboard.manager') }}"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('dashboard.manager') || request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">

                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard</span>

                </a>

                <p class="text-[10px] uppercase tracking-widest text-stone-500 px-3 mb-3 mt-5 font-semibold">
                    Laporan
                </p>

                <a href="{{ route('manajer.laporan-keuangan.index') }}"
                   class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('manajer.laporan-keuangan.*') ? 'sidebar-active' : '' }}">

                    <i data-lucide="file-bar-chart" class="w-4 h-4"></i>
                    <span>Laporan Keuangan</span>

                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full mt-2 border-t border-stone-800 pt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white">

                        <i data-lucide="log-out" class="w-4 h-4"></i>

                        <span>Keluar</span>

                    </button>
                </form>

            </nav>

        </div>

        <!-- USER -->
        <div class="p-4 border-t border-stone-800 flex items-center gap-3 bg-black/30">

            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-hotel-gold to-hotel-goldLight flex items-center justify-center text-hotel-dark font-bold">
                {{ strtoupper(substr(Auth::user()->username ?? Auth::user()->name ?? 'MH', 0, 2)) }}
            </div>

            <div>
                <h4 class="text-sm font-semibold text-white">
                    {{ Auth::user()->username ?? Auth::user()->name ?? 'Manajer' }}
                </h4>

                <p class="text-xs text-stone-500">
                    {{ Auth::user()->roles->first()->name ?? 'Manajer Hotel' }}
                </p>
            </div>

        </div>

    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col overflow-y-auto">

        <!-- TOPBAR -->
        <header class="bg-white border-b border-stone-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40">

            <div>
                <h2 class="text-sm font-bold text-hotel-dark uppercase tracking-wider">
                    RBPL HOTEL
                </h2>

                <p class="text-[11px] text-stone-500">
                    Sistem Informasi Manajemen Hotel
                </p>
            </div>

            <div class="flex items-center gap-4">

                <!-- STATUS -->
                <span class="text-xs font-semibold text-stone-700 bg-white border border-stone-200 px-4 py-2 rounded-full flex items-center gap-2">

                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>

                    Live Server

                </span>

            </div>

        </header>

        <!-- PAGE -->
        <main class="p-6 max-w-7xl w-full mx-auto space-y-6 flex-1">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-4 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-300 text-red-800 p-4 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>
