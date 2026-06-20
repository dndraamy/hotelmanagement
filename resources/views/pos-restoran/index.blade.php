<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Restoran — {{ config('app.name', 'Hotel Management') }}</title>
    <meta name="description" content="Sistem Point of Sale Restoran untuk pemesanan dan pembayaran makanan tamu hotel.">

    {{-- Google Fonts: Montserrat (Design System) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════
           DESIGN SYSTEM TOKENS
           Background : #FAF9F6
           Text       : #2D2D2D
           Accent     : #D4AF37
           Dark       : #1A1A1A
           Font       : Montserrat
        ═══════════════════════════════════════════════════ */
        :root {
            --bg-main:        #FAF9F6;
            --bg-white:       #FFFFFF;
            --bg-surface:     #F5F3EE;
            --text-primary:   #2D2D2D;
            --text-secondary: #6B6B6B;
            --text-muted:     #9B9B9B;
            --accent:         #D4AF37;
            --accent-hover:   #B8941F;
            --accent-light:   #FDF8E8;
            --accent-dark:    #1A1A1A;
            --danger:         #DC2626;
            --danger-light:   #FEF2F2;
            --success:        #16A34A;
            --success-light:  #F0FDF4;
            --border:         #E8E4DA;
            --border-focus:   #D4AF37;
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:      0 4px 12px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.04);
            --shadow-lg:      0 10px 30px rgba(0,0,0,0.10), 0 4px 12px rgba(0,0,0,0.06);
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      16px;
            --radius-full:    9999px;
            --transition:     0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── TOPBAR ─────────────────────────────────────── */
        .topbar {
            background: var(--accent-dark);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.3px;
        }
        .topbar-brand .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .topbar-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.7);
        }
        .topbar-meta strong { color: #fff; }
        .topbar-meta a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: opacity var(--transition);
        }
        .topbar-meta a:hover { opacity: 0.8; }

        /* ─── MAIN LAYOUT ────────────────────────────────── */
        .pos-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            height: calc(100vh - 60px);
            overflow: hidden;
        }

        /* ─── LEFT PANEL (MENU) ─────────────────────────── */
        .panel-menu {
            overflow-y: auto;
            padding: 24px;
            border-right: 1px solid var(--border);
            background: var(--bg-main);
        }
        .panel-menu::-webkit-scrollbar { width: 4px; }
        .panel-menu::-webkit-scrollbar-track { background: transparent; }
        .panel-menu::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .panel-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        /* Category Tabs */
        .category-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 6px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-full);
            background: var(--bg-white);
            color: var(--text-secondary);
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
        }
        .tab-btn:hover, .tab-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
            gap: 14px;
        }
        .menu-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            padding: 16px;
            cursor: pointer;
            transition: all var(--transition);
            user-select: none;
            position: relative;
            overflow: hidden;
        }
        .menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--accent-light), transparent);
            opacity: 0;
            transition: opacity var(--transition);
        }
        .menu-card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .menu-card:hover::before { opacity: 1; }
        .menu-card:active { transform: translateY(0); }

        .menu-card-emoji {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
            text-align: center;
        }
        .menu-card-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .menu-card-category {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 8px;
        }
        .menu-card-price {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--accent-hover);
        }
        .menu-card-add {
            position: absolute;
            bottom: 12px;
            right: 12px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            line-height: 1;
            opacity: 0;
            transition: all var(--transition);
        }
        .menu-card:hover .menu-card-add { opacity: 1; }

        .menu-category-heading {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 14px 0 10px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 14px;
        }
        .menu-section { margin-bottom: 24px; }
        .menu-section.hidden { display: none; }

        /* Empty menu state */
        .empty-menu {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-menu svg { margin: 0 auto 16px; opacity: 0.4; }
        .empty-menu p { font-size: 13px; }

        /* ─── RIGHT PANEL (CART) ─────────────────────────── */
        .panel-cart {
            display: flex;
            flex-direction: column;
            background: var(--bg-white);
            overflow: hidden;
        }

        .cart-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cart-header-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cart-badge {
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: var(--radius-full);
            min-width: 20px;
            text-align: center;
        }
        .cart-clear-btn {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background var(--transition);
            font-family: 'Montserrat', sans-serif;
        }
        .cart-clear-btn:hover { background: var(--danger-light); }

        /* Cart Items */
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 16px 24px;
        }
        .cart-items::-webkit-scrollbar { width: 3px; }
        .cart-items::-webkit-scrollbar-track { background: transparent; }
        .cart-items::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--bg-surface);
            animation: fadeSlideIn 0.25s ease;
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateX(12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .cart-item-emoji { font-size: 22px; flex-shrink: 0; }
        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-name {
            font-size: 12.5px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-price {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1.5px solid var(--border);
            background: var(--bg-main);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            transition: all var(--transition);
            font-family: 'Montserrat', sans-serif;
        }
        .qty-btn:hover { border-color: var(--accent); color: var(--accent); }
        .qty-btn.minus:hover { border-color: var(--danger); color: var(--danger); }
        .qty-value {
            font-size: 13px;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        /* Empty Cart */
        .cart-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: var(--text-muted);
            text-align: center;
        }
        .cart-empty svg { margin-bottom: 16px; opacity: 0.3; }
        .cart-empty p { font-size: 13px; line-height: 1.6; }

        /* ─── CART FOOTER ────────────────────────────────── */
        .cart-footer {
            border-top: 1px solid var(--border);
            padding: 20px 24px;
            background: var(--bg-white);
        }

        /* Summary */
        .cart-summary {
            margin-bottom: 16px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }
        .summary-row.total {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            padding-top: 10px;
            border-top: 1.5px solid var(--border);
            margin-top: 6px;
        }
        .summary-row.total .amount { color: var(--accent-hover); }

        /* ─── CHARGE TO ROOM SECTION ─────────────────────── */
        .charge-section {
            background: linear-gradient(135deg, #FFFDF0, #FDF8E8);
            border: 1.5px solid #E8D88A;
            border-radius: var(--radius-md);
            padding: 16px;
            margin-bottom: 12px;
            transition: all var(--transition);
        }
        .charge-section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--accent-hover);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Room Selector Dropdown */
        .room-select-wrapper {
            position: relative;
            margin-bottom: 12px;
        }
        .room-select-wrapper .select-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            pointer-events: none;
        }
        #room-select {
            width: 100%;
            padding: 10px 36px 10px 38px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--bg-white);
            color: var(--text-primary);
            font-family: 'Montserrat', sans-serif;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            outline: none;
        }
        #room-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
        }
        .room-select-wrapper .chevron-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        /* Selected Room Info Card */
        #selected-room-info {
            background: var(--bg-white);
            border: 1px solid #E8D88A;
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            display: none;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }
        #selected-room-info.visible { display: flex; }
        .room-info-icon {
            width: 34px;
            height: 34px;
            background: var(--accent-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--accent);
        }
        .room-info-text { flex: 1; min-width: 0; }
        .room-info-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .room-info-room {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .room-info-badge {
            font-size: 9.5px;
            font-weight: 700;
            background: var(--success-light);
            color: var(--success);
            padding: 2px 7px;
            border-radius: var(--radius-full);
        }

        /* ─── PRIMARY BUTTON: CHARGE TO ROOM ──────────────── */
        .btn-charge {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #D4AF37, #C09B1A);
            color: #1A1A1A;
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all var(--transition);
            box-shadow: 0 3px 10px rgba(212, 175, 55, 0.35);
            position: relative;
            overflow: hidden;
        }
        .btn-charge::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.15);
            opacity: 0;
            transition: opacity var(--transition);
        }
        .btn-charge:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(212, 175, 55, 0.45);
        }
        .btn-charge:hover::before { opacity: 1; }
        .btn-charge:active { transform: translateY(0); box-shadow: 0 2px 6px rgba(212, 175, 55, 0.3); }
        .btn-charge:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Secondary Button */
        .btn-secondary {
            width: 100%;
            padding: 11px 20px;
            background: var(--bg-main);
            color: var(--text-secondary);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-family: 'Montserrat', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all var(--transition);
            margin-top: 8px;
        }
        .btn-secondary:hover {
            border-color: var(--accent-dark);
            color: var(--text-primary);
            background: var(--bg-surface);
        }
        .btn-secondary:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ─── PESANAN PENDING LIST ──────────────────────────  */
        .pending-section {
            padding: 20px 24px;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
        }
        .pending-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }
        .pending-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .pending-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            color: inherit;
        }
        .pending-card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-sm);
        }
        .pending-card.selected {
            border-color: var(--accent);
            background: var(--accent-light);
        }
        .pending-card-info { flex: 1; min-width: 0; }
        .pending-card-id { font-size: 12px; font-weight: 700; }
        .pending-card-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .pending-card-price { font-size: 13px; font-weight: 700; color: var(--accent-hover); flex-shrink: 0; }

        /* ─── LOADING STATE ─────────────────────────────────  */
        .loading-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(26,26,26,0.3);
            border-top-color: var(--accent-dark);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes slideInAlert {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── RESPONSIVE ────────────────────────────────────── */
        @media (max-width: 768px) {
            .pos-layout {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr auto;
                height: auto;
                overflow: auto;
            }
            .panel-cart {
                height: auto;
                max-height: 50vh;
            }
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════ TOPBAR ═══════════════════════════════ --}}
<div class="topbar">
    <div class="topbar-brand">
        <div class="brand-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 11l19-9-9 19-2-8-8-2z"/>
            </svg>
        </div>
        POS Restoran
    </div>
    <div class="topbar-meta">
        <span>
            <strong>{{ now()->isoFormat('dddd, D MMMM Y') }}</strong>
        </span>
        <span>Petugas: <strong>{{ auth()->user()->name ?? 'Restoran' }}</strong></span>
        <a href="{{ route('dashboard') }}">← Dashboard</a>
    </div>
</div>

{{-- ═══════════════════════════════ MAIN LAYOUT ═══════════════════════════════ --}}
<div class="pos-layout">

    {{-- ──────────────── LEFT PANEL: MENU ──────────────── --}}
    <div class="panel-menu">

        {{-- Flash Message (atas menu) --}}
        @include('components.pos-alert')

        <div class="panel-title">Pilih Item Menu</div>

        {{-- Category Tabs --}}
        @if($menuItems->isNotEmpty())
        <div class="category-tabs" id="categoryTabs">
            <button class="tab-btn active" data-category="all" onclick="filterCategory('all', this)">
                Semua
            </button>
            @foreach($menuItems->keys() as $kategori)
            <button class="tab-btn" data-category="{{ Str::slug($kategori) }}" onclick="filterCategory('{{ Str::slug($kategori) }}', this)">
                {{ $kategori }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Menu Grid --}}
        @if($menuItems->isEmpty())
            <div class="empty-menu">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <p>Belum ada item menu.<br>Tambahkan menu di halaman Master Data.</p>
            </div>
        @else
            @foreach($menuItems as $kategori => $items)
            <div class="menu-section" data-section="{{ Str::slug($kategori) }}">
                <div class="menu-category-heading">{{ $kategori }}</div>
                <div class="menu-grid">
                    @foreach($items as $item)
                    @php
                        $emojis = ['Makanan' => '🍽️', 'Minuman' => '🥤', 'Dessert' => '🍰', 'Snack' => '🍿', 'Sarapan' => '🥞', 'Makan Siang' => '🍜', 'Makan Malam' => '🌙'];
                        $emoji = $emojis[$item->kategori] ?? '🍴';
                    @endphp
                    <div class="menu-card"
                         onclick="addToCart({{ $item->id_item }}, '{{ addslashes($item->nama_item) }}', {{ $item->harga }}, '{{ $emoji }}')"
                         title="Klik untuk tambah ke keranjang">
                        <span class="menu-card-emoji">{{ $emoji }}</span>
                        <div class="menu-card-name">{{ $item->nama_item }}</div>
                        <div class="menu-card-category">{{ $item->kategori }}</div>
                        <div class="menu-card-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                        <button class="menu-card-add" aria-label="Tambah">+</button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        @endif

        {{-- Pesanan Belum Dibayar --}}
        @if($pesananPending->isNotEmpty())
        <div style="margin-top: 28px;">
            <div class="panel-title" style="color: var(--danger); border-top: 1px solid var(--border); padding-top: 20px;">
                Pesanan Menunggu Pembayaran ({{ $pesananPending->count() }})
            </div>
            <div class="pending-list">
                @foreach($pesananPending as $po)
                <a href="{{ route('pos-restoran.index', ['pesanan_id' => $po->id_pesanan]) }}"
                   class="pending-card {{ ($selectedPesanan && $selectedPesanan->id_pesanan == $po->id_pesanan) ? 'selected' : '' }}">
                    <div class="pending-card-info">
                        <div class="pending-card-id">#{{ str_pad($po->id_pesanan, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="pending-card-meta">
                            {{ $po->tanggal_pesanan->format('d/m/Y H:i') }} •
                            {{ $po->detailPesananRestoran->count() }} item
                        </div>
                    </div>
                    <div class="pending-card-price">Rp {{ number_format($po->total_harga, 0, ',', '.') }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- end panel-menu --}}

    {{-- ──────────────── RIGHT PANEL: CART ──────────────── --}}
    <div class="panel-cart">

        {{-- Cart Header --}}
        <div class="cart-header">
            <div class="cart-header-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                Keranjang
                <span class="cart-badge" id="cartCount">0</span>
            </div>
            <button class="cart-clear-btn" onclick="clearCart()" id="clearCartBtn" style="display:none">
                Kosongkan
            </button>
        </div>

        {{-- ── SELECTED PESANAN (jika dari klik pesanan pending) ── --}}
        @if($selectedPesanan)
        <div style="padding: 16px 24px; background: var(--accent-light); border-bottom: 1px solid #E8D88A;">
            <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--accent-hover); margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span>Pesanan Dipilih #{{ str_pad($selectedPesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</span>
                <a href="{{ route('pos-restoran.cetak-dapur', $selectedPesanan->id_pesanan) }}" target="_blank" class="btn-secondary" style="margin-top: 0; padding: 4px 8px; width: auto; font-size: 10px; display: inline-flex;">Cetak Dapur</a>
            </div>
            @foreach($selectedPesanan->detailPesananRestoran as $detail)
            <div style="display:flex; justify-content:space-between; font-size:12.5px; color:var(--text-secondary); padding: 4px 0; border-bottom: 1px solid rgba(0,0,0,0.05);">
                <span>{{ $detail->itemMenu?->nama_item ?? '?' }} × {{ $detail->qty }}</span>
                <span style="font-weight:600;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div style="display:flex; justify-content:space-between; font-size:13.5px; font-weight:700; color:var(--text-primary); padding-top:10px; margin-top:4px; border-top: 1.5px solid #E8D88A;">
                <span>TOTAL</span>
                <span style="color:var(--accent-hover)">Rp {{ number_format($selectedPesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif

        {{-- Cart Items (keranjang baru dari klik menu) --}}
        <div class="cart-items" id="cartItems">
            <div class="cart-empty" id="cartEmpty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                </svg>
                <p>Keranjang kosong.<br>Klik item menu untuk menambahkan.</p>
            </div>
            <div id="cartItemList"></div>
        </div>

        {{-- Cart Footer --}}
        <div class="cart-footer">

            {{-- Order Summary --}}
            <div class="cart-summary" id="cartSummary" style="display:none">
                <div class="summary-row">
                    <span>Subtotal (<span id="summaryQty">0</span> item)</span>
                    <span>Rp <span id="summarySubtotal">0</span></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span class="amount">Rp <span id="summaryTotal">0</span></span>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════
                 CHARGE TO ROOM SECTION (PBI-45 CORE FEATURE)
            ════════════════════════════════════════════════════ --}}
            @if($selectedPesanan)
                {{-- Mode: Proses pesanan yang sudah ada --}}
                <form
                    id="chargeForm"
                    method="POST"
                    action="{{ route('pos-restoran.charge-to-room', $selectedPesanan->id_pesanan) }}"
                >
                    @csrf
                    @method('PATCH')

                    <div class="charge-section">
                        <div class="charge-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            Tagihkan ke Kamar
                        </div>

                        {{-- Dropdown Pilih Kamar --}}
                        <div class="room-select-wrapper">
                            <span class="select-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                                </svg>
                            </span>
                            <select
                                id="room-select"
                                name="id_reservasi"
                                required
                                onchange="onRoomSelected(this)"
                            >
                                <option value="">— Pilih Nomor Kamar Tamu —</option>
                                {{-- Options diisi via JavaScript dari API --}}
                            </select>
                            <span class="chevron-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </span>
                        </div>

                        {{-- Info Kamar yang Dipilih --}}
                        <div id="selected-room-info">
                            <div class="room-info-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                    <polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                            </div>
                            <div class="room-info-text">
                                <div class="room-info-name" id="roomInfoName">—</div>
                                <div class="room-info-room" id="roomInfoNumber">Kamar —</div>
                            </div>
                            <span class="room-info-badge">Checked-In</span>
                        </div>

                        {{-- Tombol Charge to Room --}}
                        <button
                            type="submit"
                            class="btn-charge"
                            id="btnChargeToRoom"
                            disabled
                        >
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                            Charge to Room
                        </button>
                    </div>
                </form>

                {{-- Tombol Bayar Tunai --}}
                <button class="btn-secondary" onclick="alert('Fitur pembayaran tunai akan segera tersedia.')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Bayar Tunai
                </button>

            @else
                {{-- Mode: Keranjang baru (belum ada pesanan dipilih) --}}
                <div class="charge-section" id="chargeNewSection" style="display:none">
                    <div class="charge-section-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Tagihkan ke Kamar
                    </div>
                    <div class="room-select-wrapper">
                        <span class="select-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                        </span>
                        <select
                            id="room-select-new"
                            name="id_reservasi"
                            onchange="onRoomSelectedNew(this)"
                        >
                            <option value="">— Pilih Nomor Kamar Tamu —</option>
                        </select>
                        <span class="chevron-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </span>
                    </div>
                    <div id="selected-room-info-new" class="selected-room-info" style="display:none; background:var(--bg-white); border:1px solid #E8D88A; border-radius:var(--radius-sm); padding:10px 12px; gap:10px; align-items:center; margin-bottom:12px; flex-direction:row;">
                        <div class="room-info-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="room-info-text">
                            <div class="room-info-name" id="roomInfoNameNew">—</div>
                            <div class="room-info-room" id="roomInfoNumberNew">Kamar —</div>
                        </div>
                        <span class="room-info-badge">Checked-In</span>
                    </div>
                </div>

                {{-- Form untuk buat pesanan baru + checkout --}}
                <form id="newOrderForm" method="POST" action="{{ route('pos-restoran.buat-pesanan') }}" style="display:none">
                    @csrf
                    <div id="cartFormItems"></div>
                    <button
                        type="button"
                        class="btn-charge"
                        id="btnChargeNew"
                        disabled
                        onclick="submitNewOrderWithCharge()"
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Charge to Room
                    </button>
                </form>

                <div id="emptyCartActions" style="text-align:center; padding: 10px 0; color: var(--text-muted); font-size:12px;">
                    Tambahkan item ke keranjang untuk melanjutkan.
                </div>

            @endif

        </div>{{-- end cart-footer --}}
    </div>{{-- end panel-cart --}}

</div>{{-- end pos-layout --}}

{{-- ═══════════════════════════════ JAVASCRIPT ═══════════════════════════════ --}}
<script>
// ── Keranjang State ────────────────────────────────────────────────────────
let cart = {};
// { id_item: { id_item, nama, harga, emoji, qty } }

const guestData = {}; // { id_reservasi: { nama_tamu, nomor_kamar } }

// ── Inisialisasi: Muat Daftar Tamu Checked-In ─────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadCheckedInGuests();
    renderCart();
});

async function loadCheckedInGuests() {
    try {
        const res = await fetch('{{ route('pos-restoran.tamu-checkedin') }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        // Populate dropdown(s)
        const selectors = ['#room-select', '#room-select-new'].map(s => document.querySelector(s)).filter(Boolean);

        selectors.forEach(select => {
            // Simpan option default
            const defaultOpt = select.options[0];
            select.innerHTML = '';
            select.appendChild(defaultOpt);

            if (data.length === 0) {
                const opt = new Option('— Tidak ada tamu Checked-In saat ini —', '');
                opt.disabled = true;
                select.appendChild(opt);
                return;
            }

            data.forEach(guest => {
                const opt = new Option(guest.label, guest.id_reservasi);
                select.appendChild(opt);
                // Simpan data guest untuk info card
                guestData[guest.id_reservasi] = { nama: guest.nama_tamu, kamar: guest.nomor_kamar };
            });
        });

    } catch (e) {
        console.error('Gagal memuat daftar tamu:', e);
    }
}

// ── Handler Pilih Kamar (mode pesanan yang sudah ada) ─────────────────────
function onRoomSelected(select) {
    const val = select.value;
    const btn = document.getElementById('btnChargeToRoom');
    const info = document.getElementById('selected-room-info');

    if (val && guestData[val]) {
        document.getElementById('roomInfoName').textContent = guestData[val].nama;
        document.getElementById('roomInfoNumber').textContent = 'Kamar ' + guestData[val].kamar;
        info.classList.add('visible');
        if (btn) btn.disabled = false;
    } else {
        info.classList.remove('visible');
        if (btn) btn.disabled = true;
    }
}

// ── Handler Pilih Kamar (mode keranjang baru) ─────────────────────────────
function onRoomSelectedNew(select) {
    const val = select.value;
    const btn = document.getElementById('btnChargeNew');
    const info = document.getElementById('selected-room-info-new');

    if (val && guestData[val]) {
        document.getElementById('roomInfoNameNew').textContent = guestData[val].nama;
        document.getElementById('roomInfoNumberNew').textContent = 'Kamar ' + guestData[val].kamar;
        if (info) info.style.display = 'flex';
        updateNewCartButton();
    } else {
        if (info) info.style.display = 'none';
        if (btn) btn.disabled = true;
    }
}

// ── Keranjang: Tambah Item ─────────────────────────────────────────────────
function addToCart(id, nama, harga, emoji) {
    if (cart[id]) {
        cart[id].qty += 1;
    } else {
        cart[id] = { id_item: id, nama, harga, emoji, qty: 1 };
    }
    renderCart();

    // Micro-feedback: animasi pulse pada badge
    const badge = document.getElementById('cartCount');
    badge.style.transform = 'scale(1.4)';
    setTimeout(() => badge.style.transform = 'scale(1)', 200);
}

// ── Keranjang: Ubah Kuantitas ──────────────────────────────────────────────
function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
        delete cart[id];
    }
    renderCart();
}

// ── Keranjang: Kosongkan ───────────────────────────────────────────────────
function clearCart() {
    if (!Object.keys(cart).length) return;
    if (confirm('Kosongkan semua item dari keranjang?')) {
        cart = {};
        renderCart();
    }
}

// ── Keranjang: Render UI ───────────────────────────────────────────────────
function renderCart() {
    const items = Object.values(cart);
    const isEmpty = items.length === 0;

    // Toggle empty state
    document.getElementById('cartEmpty').style.display = isEmpty ? 'flex' : 'none';
    document.getElementById('cartEmpty').style.flexDirection = 'column';
    const clearBtn = document.getElementById('clearCartBtn');
    if (clearBtn) clearBtn.style.display = isEmpty ? 'none' : 'block';

    // Render item list
    const listEl = document.getElementById('cartItemList');
    listEl.innerHTML = items.map(item => `
        <div class="cart-item" id="cart-item-${item.id_item}">
            <span class="cart-item-emoji">${item.emoji}</span>
            <div class="cart-item-info">
                <div class="cart-item-name">${item.nama}</div>
                <div class="cart-item-price">Rp ${formatNum(item.harga)} / item</div>
            </div>
            <div class="cart-item-controls">
                <button class="qty-btn minus" onclick="changeQty(${item.id_item}, -1)" title="Kurangi">−</button>
                <span class="qty-value">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${item.id_item}, 1)" title="Tambah">+</button>
            </div>
        </div>
    `).join('');

    // Update count badge
    const totalQty = items.reduce((s, i) => s + i.qty, 0);
    document.getElementById('cartCount').textContent = totalQty;

    // Update summary
    const total = items.reduce((s, i) => s + (i.harga * i.qty), 0);
    const summaryEl = document.getElementById('cartSummary');
    if (summaryEl) {
        summaryEl.style.display = isEmpty ? 'none' : 'block';
        document.getElementById('summaryQty').textContent = totalQty;
        document.getElementById('summarySubtotal').textContent = formatNum(total);
        document.getElementById('summaryTotal').textContent = formatNum(total);
    }

    // Toggle charge section (mode keranjang baru)
    const chargeSection = document.getElementById('chargeNewSection');
    const emptyActions  = document.getElementById('emptyCartActions');
    const newOrderForm  = document.getElementById('newOrderForm');

    if (chargeSection) chargeSection.style.display = isEmpty ? 'none' : 'block';
    if (emptyActions)  emptyActions.style.display  = isEmpty ? 'block' : 'none';
    if (newOrderForm)  newOrderForm.style.display   = isEmpty ? 'none' : 'block';

    // Rebuild hidden form inputs untuk submit
    if (!isEmpty && newOrderForm) {
        const container = document.getElementById('cartFormItems');
        container.innerHTML = items.map((item, idx) => `
            <input type="hidden" name="items[${idx}][id_item]" value="${item.id_item}">
            <input type="hidden" name="items[${idx}][qty]"     value="${item.qty}">
        `).join('');
    }

    updateNewCartButton();

    // Load tamu jika belum
    if (!isEmpty) {
        const selectNew = document.getElementById('room-select-new');
        if (selectNew && selectNew.options.length <= 1) {
            loadCheckedInGuests();
        }
    }
}

// ── Tombol Charge Keranjang Baru: Aktif jika ada item + kamar dipilih ──────
function updateNewCartButton() {
    const btn = document.getElementById('btnChargeNew');
    if (!btn) return;
    const hasItems = Object.keys(cart).length > 0;
    const selectNew = document.getElementById('room-select-new');
    const hasRoom = selectNew && selectNew.value;
    btn.disabled = !(hasItems && hasRoom);
}

// ── Submit Keranjang Baru + Charge (via form hidden field) ─────────────────
function submitNewOrderWithCharge() {
    const selectNew = document.getElementById('room-select-new');
    if (!selectNew || !selectNew.value) {
        alert('Mohon pilih kamar tamu terlebih dahulu.');
        return;
    }
    // Tambahkan id_reservasi ke form
    const form = document.getElementById('newOrderForm');
    let reservasiInput = form.querySelector('input[name="id_reservasi_checkout"]');
    if (!reservasiInput) {
        reservasiInput = document.createElement('input');
        reservasiInput.type = 'hidden';
        reservasiInput.name = 'id_reservasi_checkout';
        form.appendChild(reservasiInput);
    }
    reservasiInput.value = selectNew.value;
    form.submit();
}

// ── Filter Kategori Menu ───────────────────────────────────────────────────
function filterCategory(category, btn) {
    // Update tab aktif
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Toggle section visibility
    document.querySelectorAll('.menu-section').forEach(section => {
        if (category === 'all' || section.dataset.section === category) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    });
}

// ── Helper: Format Number ─────────────────────────────────────────────────
function formatNum(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

// ── Konfirmasi Form Charge to Room ────────────────────────────────────────
const chargeForm = document.getElementById('chargeForm');
if (chargeForm) {
    chargeForm.addEventListener('submit', function(e) {
        const btn = document.getElementById('btnChargeToRoom');
        const roomSelect = document.getElementById('room-select');
        if (!roomSelect.value) {
            e.preventDefault();
            roomSelect.focus();
            return;
        }
        // Loading state
        btn.disabled = true;
        btn.innerHTML = `<span class="loading-spinner"></span> Memproses...`;
    });
}
</script>

</body>
</html>
