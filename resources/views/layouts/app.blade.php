<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name', 'RBPL Hotel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #FAF9F6;
            color: #2D2D2D;
        }

        .sidebar-active {
            background-color: #2D2D2D;
            border-left: 4px solid #D4AF37;
            color: #D4AF37 !important;
        }
    </style>

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
</head>

<body class="overflow-x-hidden">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-hotel-dark hidden md:flex flex-col justify-between border-r border-stone-800 text-stone-300">

            <div>

                <!-- LOGO -->
                <div class="p-6 flex flex-col items-center border-b border-stone-800">

                    <div class="w-16 h-16 rounded-2xl bg-hotel-gold flex items-center justify-center text-hotel-dark font-extrabold text-xl">
                        H
                    </div>

                    <span class="text-xs font-bold tracking-[0.2em] text-hotel-gold mt-3">
                        RBPL HOTEL
                    </span>

                    <span class="text-[9px] text-stone-500 uppercase tracking-wider mt-1">
                        @role('Staf Gudang') Inventory Management @endrole
                        @role('Manajer Hotel') Management & Report @endrole
                    </span>

                </div>

                <!-- MENU -->
                <nav class="mt-6 px-4 space-y-1">

                    <p class="text-[10px] uppercase tracking-widest text-stone-500 px-3 mb-2 font-semibold">
                        Modul Utama
                    </p>

                @role('Staf Gudang')
                    <a href="{{ route('inventory.index') }}"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('inventory.index') ? 'sidebar-active' : '' }}">

                        <div class="flex items-center gap-3">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Dashboard & Stok</span>
                        </div>

                    @if(isset($totalWarning) && $totalWarning ?? 0> 0)
                        <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded-full">
                            {{ $totalWarning ?? 0}}
                        </span>
                        @endif

                    </a>

                    <a href="{{ route('inventory.mutasi') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('inventory.mutasi') ? 'sidebar-active' : '' }}">

                        <i data-lucide="arrow-left-right" class="w-4 h-4 text-hotel-gold"></i>

                        <span>Mutasi Stok</span>

                    </a>

                    <a href="{{ route('inventory.laporan') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('inventory.laporan') ? 'sidebar-active' : '' }}">

                        <i data-lucide="history" class="w-4 h-4"></i>

                        <span>Riwayat Laporan</span>

                    </a>

                    <a href="{{ route('hrd.dashboard.hrd.jadwal-shift.index') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('hrd.dashboard.hrd.jadwal-shift.*') ? 'sidebar-active' : '' }}">

                        <i data-lucide="calendar-clock" class="w-4 h-4"></i>

                        <span>Jadwal Shift</span>

                    </a>

                    <a href="{{ route('hrd.dashboard.hrd.jadwalkerja') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('hrd.dashboard.hrd.jadwalkerja') ? 'sidebar-active' : '' }}">

                        <i data-lucide="calendar-days" class="w-4 h-4"></i>

                        <span>Jadwal Kerja</span>

                    </a>
                @endrole

                @role('Manajer Hotel') 
                    <a href="{{ route('manajer.laporan-keuangan.index') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white {{ request()->routeIs('manajer.laporan-keuangan.*') ? 'sidebar-active' : '' }}">
            
                        <i data-lucide="file-bar-chart" class="w-4 h-4 text-hotel-gold"></i>
            
                        <span>Laporan Keuangan</span>
                    
                    </a>
                @endrole 

                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2 border-t border-stone-800 pt-2">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-stone-400 hover:bg-stone-800 hover:text-white">

                            <i data-lucide="log-out" class="w-4 h-4"></i>

                            <span>Keluar</span>

                        </button>
                    </form>

                </nav>

            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="p-4 border-t border-stone-800 flex items-center space-x-3 bg-stone-950/40">

                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-hotel-gold to-hotel-goldLight flex items-center justify-center text-hotel-dark font-bold text-sm">
                    @role('Staf Gudang') SG @endrole
                    @role('Manajer Hotel') MN @endrole
                </div>

                <div>
                    <h4 class="text-xs font-semibold text-white">
                        @role('Staf Gudang') Staf Gudang @endrole
                        @role('Manajer Hotel') Manajer Hotel @endrole
                    </h4>

                    <p class="text-[10px] text-stone-500">
                        @role('Staf Gudang') Inventory Officer @endrole
                        @role('Manajer Hotel') Hotel General Manager @endrole
                    </p>
                </div>

            </div>

        </aside>

        <!-- CONTENT -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- HEADER -->
            <header class="bg-white border-b border-stone-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40">
                
                <div>
                    <h2 class="text-sm font-bold text-hotel-dark uppercase tracking-wider">
                        RBPL Hotel
                    </h2>
                </div> 

                    <!-- LIVE SERVER -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-stone-700 bg-hotel-bg border border-stone-200 px-3 py-1.5 rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Live Server
                        </span>
                    </div> 

            </header>

            <!-- CONTENT -->
            <main class="p-6 max-w-7xl w-full mx-auto space-y-6 flex-1">

                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-4 rounded-xl text-xs font-bold">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-rose-50 border border-rose-300 text-rose-800 p-4 rounded-xl text-xs font-bold">
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}

            </main>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
