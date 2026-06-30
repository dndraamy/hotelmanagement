<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Room Charge — {{ config('app.name', 'Hotel Management') }}</title>
    <meta name="description" content="Terminal transaksi Room Charge: bebankan tagihan langsung ke kamar tamu yang sedang Check-In.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════════
           DESIGN TOKENS — Cool Gray / Blue Enterprise Theme
        ═══════════════════════════════════════════════════════════ */
        :root {
            /* Surfaces */
            --bg-app:         #F1F5F9;
            --bg-sidebar:     #0F172A;
            --bg-sidebar-alt: #1E293B;
            --bg-white:       #FFFFFF;
            --bg-surface:     #F8FAFC;

            /* Brand */
            --brand:          #2563EB;
            --brand-hover:    #1D4ED8;
            --brand-light:    #EFF6FF;
            --brand-dark:     #1E3A8A;
            --accent-gold:    #D4AF37;

            /* Text */
            --text-primary:   #0F172A;
            --text-secondary: #475569;
            --text-muted:     #94A3B8;
            --text-inverse:   #F8FAFC;

            /* Semantic */
            --danger:         #EF4444;
            --danger-light:   #FEF2F2;
            --danger-border:  #FECACA;
            --success:        #10B981;
            --success-light:  #ECFDF5;
            --warning:        #F59E0B;
            --warning-light:  #FFFBEB;

            /* Borders */
            --border:         #E2E8F0;
            --border-focus:   #2563EB;

            /* Shadows */
            --shadow-xs:  0 1px 2px rgba(0,0,0,0.05);
            --shadow-sm:  0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:  0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.04);
            --shadow-lg:  0 12px 32px rgba(0,0,0,0.10), 0 4px 12px rgba(0,0,0,0.06);
            --shadow-blue: 0 4px 16px rgba(37, 99, 235, 0.25);

            /* Radii */
            --r-sm:   6px;
            --r-md:   10px;
            --r-lg:   14px;
            --r-xl:   20px;
            --r-full: 9999px;

            /* Sidebar */
            --sidebar-w: 260px;
            --topbar-h:  64px;

            /* Transition */
            --ease: 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-app);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ─── SIDEBAR ──────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        /* Brand area */
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--accent-gold), #C09B1A);
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(212,175,55,0.35);
        }
        .brand-text-main {
            font-size: 14px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: 0.5px;
        }
        .brand-text-sub {
            font-size: 10px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Admin profile card — ditempatkan di BAWAH sidebar */
        .sidebar-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            margin-bottom: 8px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: var(--r-md);
        }
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--r-full);
            background: #4B5563;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #E2E8F0;
            flex-shrink: 0;
            letter-spacing: 0.3px;
        }
        .profile-info { flex: 1; min-width: 0; }
        .profile-name {
            font-size: 12px;
            font-weight: 600;
            color: #E2E8F0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-role {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Nav group */
        .sidebar-nav { flex: 1; padding: 12px 12px 0; }
        .nav-group { margin-bottom: 20px; }
        .nav-group-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #4B5563;
            padding: 0 8px;
            margin-bottom: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            color: #94A3B8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all var(--ease);
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .nav-item svg { flex-shrink: 0; opacity: 0.7; transition: opacity var(--ease); }
        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: #E2E8F0;
        }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active {
            background: rgba(37, 99, 235, 0.15);
            color: #60A5FA;
            font-weight: 600;
        }
        .nav-item.active svg { opacity: 1; color: #60A5FA; }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 12px;
            width: 3px;
            height: 18px;
            background: var(--brand);
            border-radius: var(--r-full);
        }
        .nav-item { position: relative; }
        .nav-badge {
            margin-left: auto;
            background: var(--brand);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: var(--r-full);
        }

        /* Sidebar footer — contains profile + logout */
        .sidebar-footer {
            padding: 12px 12px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* ─── TOPBAR ────────────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            z-index: 100;
            box-shadow: var(--shadow-xs);
        }
        .topbar-left { display: flex; flex-direction: column; gap: 2px; }
        .topbar-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .topbar-breadcrumb {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar-breadcrumb span { color: var(--text-secondary); font-weight: 500; }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar-live {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--text-secondary);
            font-weight: 500;
        }
        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: var(--r-full);
            background: var(--success);
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
            50%       { box-shadow: 0 0 0 5px rgba(16,185,129,0); }
        }
        .topbar-logout {
            font-size: 12px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            transition: all var(--ease);
        }
        .topbar-logout:hover {
            background: var(--danger-light);
            color: var(--danger);
            border-color: var(--danger-border);
        }

        /* ─── MAIN CONTENT ──────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 32px 36px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ─── PAGE HEADER ───────────────────────────────────────── */
        .page-header { margin-bottom: 28px; }
        .page-header-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .page-header-breadcrumb a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 500;
            transition: opacity var(--ease);
        }
        .page-header-breadcrumb a:hover { opacity: 0.75; }
        .breadcrumb-sep { color: var(--border); }
        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-title-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-blue);
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 5px;
            font-weight: 400;
        }

        /* ─── CARDS ─────────────────────────────────────────────── */
        .card {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 24px;
            animation: cardIn 0.35s var(--ease) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card:nth-child(2) { animation-delay: 0.06s; }
        .card-header {
            padding: 20px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--r-sm);
            background: var(--brand-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand);
        }
        .card-subtitle {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 3px;
            font-weight: 400;
        }
        .card-body { padding: 20px 24px 24px; }

        /* ─── SEARCH BAR ────────────────────────────────────────── */
        .search-wrapper {
            position: relative;
            margin-bottom: 18px;
        }
        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1.5px solid var(--border);
            border-radius: var(--r-md);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--bg-surface);
            transition: all var(--ease);
            outline: none;
        }
        .search-input:focus {
            border-color: var(--brand);
            background: var(--bg-white);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
        }
        .search-input::placeholder { color: var(--text-muted); }
        .search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 2px;
            display: none;
            border-radius: 4px;
            transition: color var(--ease);
        }
        .search-clear:hover { color: var(--text-primary); }
        .search-clear.visible { display: flex; }

        /* ─── TABLE ─────────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead tr {
            background: var(--bg-surface);
            border-bottom: 2px solid var(--border);
        }
        .data-table th {
            padding: 10px 16px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            text-align: left;
            white-space: nowrap;
        }
        .data-table th:last-child { text-align: center; }
        .data-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background var(--ease);
        }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: var(--bg-surface); }
        .data-table tbody tr.row-selected {
            background: var(--brand-light);
            border-color: rgba(37,99,235,0.2);
        }
        .data-table td {
            padding: 13px 16px;
            font-size: 13px;
            vertical-align: middle;
        }
        .td-room {
            font-weight: 700;
            color: var(--text-primary);
        }
        .room-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: var(--r-sm);
            letter-spacing: 0.3px;
        }
        .td-name { font-weight: 500; color: var(--text-primary); }
        .td-checkin {
            font-size: 12px;
            color: var(--text-secondary);
        }
        .td-action { text-align: center; }
        .btn-select-guest {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: var(--brand-light);
            color: var(--brand);
            border: 1.5px solid rgba(37,99,235,0.2);
            border-radius: var(--r-sm);
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--ease);
            white-space: nowrap;
        }
        .btn-select-guest:hover {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
            box-shadow: var(--shadow-blue);
            transform: translateY(-1px);
        }
        .btn-select-guest.selected-state {
            background: var(--success-light);
            color: var(--success);
            border-color: rgba(16,185,129,0.3);
        }

        /* Empty state */
        .table-empty {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
        }
        .table-empty svg { margin: 0 auto 14px; opacity: 0.35; display: block; }
        .table-empty p { font-size: 13px; line-height: 1.6; }
        .no-results { display: none; }
        .no-results.visible { display: table-row; }

        /* ─── SELECTED GUEST BANNER ─────────────────────────────── */
        .guest-selected-banner {
            display: none;
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            border: 1.5px solid rgba(37,99,235,0.25);
            border-radius: var(--r-md);
            padding: 14px 18px;
            margin-bottom: 18px;
            align-items: center;
            gap: 14px;
            animation: bannerSlide 0.3s var(--ease);
        }
        @keyframes bannerSlide {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .guest-selected-banner.visible { display: flex; }
        .banner-icon {
            width: 40px;
            height: 40px;
            background: var(--brand);
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
        }
        .banner-info { flex: 1; }
        .banner-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--brand-dark);
        }
        .banner-meta {
            font-size: 11.5px;
            color: var(--brand);
            margin-top: 2px;
        }
        .banner-clear {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: var(--r-sm);
            transition: all var(--ease);
            display: flex;
            align-items: center;
        }
        .banner-clear:hover { color: var(--danger); background: var(--danger-light); }

        /* ─── FORM CARD (Card 2) ───────────────────────────────── */
        .form-card {
            display: none;
        }
        .form-card.visible { display: block; }
        .form-grid { display: grid; gap: 18px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: 0.2px;
        }
        .form-label .required { color: var(--danger); margin-left: 2px; }
        .form-input, .form-select, .form-textarea {
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--r-md);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--bg-surface);
            transition: all var(--ease);
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--brand);
            background: var(--bg-white);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
        }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); }
        .form-select { appearance: none; -webkit-appearance: none; cursor: pointer; }
        .select-wrapper { position: relative; }
        .select-chevron {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }
        .form-textarea { resize: vertical; min-height: 90px; line-height: 1.6; }
        .input-prefix-wrapper { position: relative; }
        .input-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            pointer-events: none;
        }
        .input-prefix-wrapper .form-input { padding-left: 44px; }
        .form-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Two-column form grid */
        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ─── SUMMARY AREA ──────────────────────────────────────── */
        .summary-area {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--r-md);
            padding: 18px 20px;
            margin-top: 22px;
        }
        .summary-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 13px;
            color: var(--text-secondary);
        }
        .summary-row + .summary-row { border-top: 1px solid var(--border); }
        .summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0 0;
            border-top: 2px solid var(--border);
            margin-top: 6px;
        }
        .summary-total-label {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .summary-total-amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--brand);
            letter-spacing: -0.5px;
        }
        .summary-empty-hint {
            font-size: 12px;
            color: var(--text-muted);
            font-style: italic;
        }

        /* ─── ACTION BUTTONS ────────────────────────────────────── */
        .action-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .btn-cancel {
            padding: 11px 22px;
            border: 1.5px solid var(--border);
            border-radius: var(--r-md);
            background: var(--bg-white);
            color: var(--text-secondary);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: all var(--ease);
        }
        .btn-cancel:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: var(--danger-light);
        }
        .btn-confirm {
            padding: 11px 26px;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            border: none;
            border-radius: var(--r-md);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all var(--ease);
            box-shadow: var(--shadow-blue);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }
        .btn-confirm::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.12);
            opacity: 0;
            transition: opacity var(--ease);
        }
        .btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(37,99,235,0.35); }
        .btn-confirm:hover::before { opacity: 1; }
        .btn-confirm:active { transform: translateY(0); }
        .btn-confirm:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ─── FLASH ALERT ───────────────────────────────────────── */
        .flash-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--r-md);
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 500;
            animation: cardIn 0.3s var(--ease);
            line-height: 1.5;
        }
        .flash-alert.success { background: var(--success-light); color: #065F46; border: 1px solid rgba(16,185,129,0.3); }
        .flash-alert.error   { background: var(--danger-light);  color: #991B1B; border: 1px solid rgba(239,68,68,0.3); }
        .flash-alert.info    { background: var(--brand-light);   color: var(--brand-dark); border: 1px solid rgba(37,99,235,0.25); }
        .flash-alert svg { flex-shrink: 0; margin-top: 1px; }
        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.55;
            padding: 0 0 0 8px;
            transition: opacity var(--ease);
            flex-shrink: 0;
        }
        .flash-close:hover { opacity: 1; }

        /* Validation error */
        .field-error {
            font-size: 11.5px;
            color: var(--danger);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .form-input.has-error, .form-select.has-error, .form-textarea.has-error {
            border-color: var(--danger);
        }
        .form-input.has-error:focus, .form-select.has-error:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        }

        /* Pulse animation for form reveal */
        @keyframes formReveal {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .form-card.visible {
            animation: formReveal 0.4s var(--ease);
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════ SIDEBAR ═══════════════════════════════════ --}}
<aside class="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">
                {{-- Hexagon / building icon matching RBPL HOTEL logo in reference --}}
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A1A1A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 19 6.5 19 17.5 12 22 5 17.5 5 6.5 12 2"/>
                    <line x1="12" y1="2" x2="12" y2="22"/>
                    <line x1="5" y1="6.5" x2="19" y2="6.5"/>
                    <line x1="5" y1="17.5" x2="19" y2="17.5"/>
                </svg>
            </div>
            <div>
                <div class="brand-text-main">RBPL HOTEL</div>
                <div class="brand-text-sub">Management System</div>
            </div>
        </div>
    </div>


    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-group-label">Modul Reservasi</div>
            <a href="{{ route('dashboard') }}" class="nav-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard Reservasi
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                </svg>
                Buat Reservasi
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                Cari Kamar
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Restaurant & POS</div>
            <a href="{{ route('pos-restoran.index') }}" class="nav-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 00-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                </svg>
                POS Restoran
            </a>
            <a href="{{ route('room-charge.index') }}" class="nav-item active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Room Charge
            </a>
        </div>
    </nav>

    {{-- Sidebar footer: profil admin + logout (sesuai foto referensi) --}}
    <div class="sidebar-footer">
        {{-- Admin Profile Card --}}
        <div class="sidebar-profile">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="profile-role">{{ auth()->user()->email ?? 'Resepsionist' }}</div>
            </div>
        </div>
        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item" style="color:#64748B;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════ TOPBAR ═══════════════════════════════════ --}}
<header class="topbar">
    <div class="topbar-left">
        <div class="topbar-title">RBPL HOTEL</div>
        <div class="topbar-breadcrumb">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22V9l9-7 9 7v13"/></svg>
            <a href="{{ route('dashboard') }}">Sistem Informasi Manajemen Reservasi</a>
        </div>
    </div>
    <div class="topbar-right">
        <div class="topbar-live">
            <div class="live-dot"></div>
            Live Server
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="topbar-logout">Logout</button>
        </form>
    </div>
</header>

{{-- ═══════════════════════════════════ MAIN CONTENT ═══════════════════════════════════ --}}
<main class="main-content">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-breadcrumb">
            <a href="{{ route('pos-restoran.index') }}">Restaurant &amp; POS</a>
            <span class="breadcrumb-sep">›</span>
            <span>Room Charge</span>
        </div>
        <div class="page-title">
            <div class="page-title-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
            Room Charge
        </div>
        <p class="page-subtitle">Bebankan tagihan tambahan langsung ke kamar tamu yang sedang Check-In.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flash-alert success" id="flashAlert" role="alert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ session('success') }}</span>
        <button class="flash-close" onclick="document.getElementById('flashAlert').remove()" aria-label="Tutup">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="flash-alert error" id="flashAlert" role="alert">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        <span>{{ session('error') }}</span>
        <button class="flash-close" onclick="document.getElementById('flashAlert').remove()" aria-label="Tutup">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    @endif

    {{-- ═══════════ FORM wrapping both cards ═══════════ --}}
    <form id="roomChargeForm" method="POST" action="{{ route('room-charge.store') }}" novalidate>
        @csrf
        <input type="hidden" name="id_reservasi" id="hiddenReservasiId" value="{{ old('id_reservasi') }}">

        {{-- ══════════════ CARD 1: Pilih Tamu ══════════════ --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        Pilih Tamu (In-House Guests)
                    </div>
                    <div class="card-subtitle">Cari dan pilih tamu yang sedang Check-In untuk dibebankan tagihan.</div>
                </div>
                <div id="guestCountBadge" style="font-size:11px;font-weight:600;color:var(--text-muted);background:var(--bg-surface);border:1px solid var(--border);padding:5px 12px;border-radius:var(--r-full);">
                    {{ $tamuCheckedIn->count() }} Tamu Aktif
                </div>
            </div>
            <div class="card-body">

                {{-- Guest Selected Banner --}}
                <div class="guest-selected-banner" id="guestBanner">
                    <div class="banner-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="banner-info">
                        <div class="banner-name" id="bannerName">—</div>
                        <div class="banner-meta" id="bannerMeta">—</div>
                    </div>
                    <button type="button" class="banner-clear" id="bannerClearBtn" title="Batal Pilih" onclick="clearSelection()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- Search bar --}}
                <div class="search-wrapper">
                    <span class="search-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="guestSearchInput"
                        class="search-input"
                        placeholder="Cari berdasarkan No. Kamar atau Nama Tamu…"
                        autocomplete="off"
                        oninput="filterGuests(this.value)"
                    >
                    <button type="button" class="search-clear" id="searchClearBtn" onclick="clearSearch()" aria-label="Hapus pencarian">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- Table --}}
                @if($tamuCheckedIn->isEmpty())
                <div class="table-empty">
                    <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <p>Tidak ada tamu yang sedang Check-In saat ini.<br>Pastikan data reservasi sudah diperbarui.</p>
                </div>
                @else
                <div style="overflow-x:auto;border-radius:var(--r-md);border:1px solid var(--border);">
                    <table class="data-table" id="guestTable">
                        <thead>
                            <tr>
                                <th>No. Kamar</th>
                                <th>Nama Tamu</th>
                                <th>Tanggal Check-In</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="guestTableBody">
                            @foreach($tamuCheckedIn as $tamu)
                            <tr
                                class="guest-row"
                                data-id="{{ $tamu->id_reservasi }}"
                                data-name="{{ $tamu->nama_tamu }}"
                                data-room="{{ $tamu->nomor_kamar }}"
                                data-checkin="{{ $tamu->tgl_checkin }}"
                                data-search="{{ strtolower($tamu->nomor_kamar . ' ' . $tamu->nama_tamu) }}"
                            >
                                <td class="td-room">
                                    <span class="room-badge">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 22V9l9-7 9 7v13"/></svg>
                                        {{ $tamu->nomor_kamar }}
                                    </span>
                                </td>
                                <td class="td-name">{{ $tamu->nama_tamu }}</td>
                                <td class="td-checkin">
                                    {{ $tamu->tgl_checkin ? \Carbon\Carbon::parse($tamu->tgl_checkin)->format('d M Y') : '—' }}
                                </td>
                                <td class="td-action">
                                    <button
                                        type="button"
                                        class="btn-select-guest"
                                        id="selectBtn_{{ $tamu->id_reservasi }}"
                                        onclick="selectGuest('{{ $tamu->id_reservasi }}', '{{ addslashes($tamu->nama_tamu) }}', '{{ $tamu->nomor_kamar }}', '{{ $tamu->tgl_checkin }}')"
                                    >
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Pilih Tamu
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="no-results" id="noResultsRow">
                                <td colspan="4">
                                    <div class="table-empty" style="padding:30px 20px;">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                        <p>Tamu tidak ditemukan.<br>Coba kata kunci lain.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif

            </div>{{-- card-body --}}
        </div>{{-- card 1 --}}

        {{-- ══════════════ CARD 2: Detail Tagihan (hidden until guest selected) ══════════════ --}}
        <div class="card form-card" id="billingCard">
            <div class="card-header">
                <div>
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </div>
                        Detail Tagihan Tambahan
                    </div>
                    <div class="card-subtitle">Isi detail tagihan yang akan dibebankan ke kamar tamu terpilih.</div>
                </div>
            </div>
            <div class="card-body">

                <div class="form-grid">
                    {{-- Deskripsi Tagihan --}}
                    <div class="form-group">
                        <label for="deskripsi" class="form-label">
                            Deskripsi Tagihan <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="deskripsi"
                            name="deskripsi"
                            class="form-input {{ $errors->has('deskripsi') ? 'has-error' : '' }}"
                            placeholder="Contoh: Pesanan Restoran Ala Carte, Minibar, Layanan Spa…"
                            value="{{ old('deskripsi') }}"
                            maxlength="255"
                        >
                        @error('deskripsi')
                        <div class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Nominal & Kategori --}}
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="nominal" class="form-label">
                                Nominal (Rp) <span class="required">*</span>
                            </label>
                            <div class="input-prefix-wrapper">
                                <span class="input-prefix">Rp</span>
                                <input
                                    type="text"
                                    id="nominal"
                                    name="nominal"
                                    class="form-input {{ $errors->has('nominal') ? 'has-error' : '' }}"
                                    placeholder="0"
                                    value="{{ old('nominal') }}"
                                    inputmode="numeric"
                                    oninput="formatNominal(this)"
                                    onblur="updateSummary()"
                                >
                            </div>
                            @error('nominal')
                            <div class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="form-label">
                                Kategori <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select
                                    id="kategori"
                                    name="kategori"
                                    class="form-select form-input {{ $errors->has('kategori') ? 'has-error' : '' }}"
                                    onchange="updateSummary()"
                                >
                                    <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>— Pilih Kategori —</option>
                                    @foreach(['F&B', 'Minibar', 'Spa', 'Miscellaneous'] as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
                                </select>
                                <span class="select-chevron">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                            </div>
                            @error('kategori')
                            <div class="field-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group">
                        <label for="catatan" class="form-label">
                            Catatan Tambahan
                            <span style="font-weight:400;color:var(--text-muted);"> (opsional)</span>
                        </label>
                        <textarea
                            id="catatan"
                            name="catatan"
                            class="form-textarea"
                            placeholder="Informasi tambahan, nomor meja, waktu layanan, dll…"
                            maxlength="1000"
                        >{{ old('catatan') }}</textarea>
                        <span class="form-hint">Maksimal 1000 karakter.</span>
                    </div>
                </div>{{-- /form-grid --}}

                {{-- Summary Area --}}
                <div class="summary-area" id="summaryArea">
                    <div class="summary-label">Ringkasan Tagihan</div>
                    <div class="summary-row">
                        <span>Tamu</span>
                        <span id="summaryGuest" style="font-weight:600;color:var(--text-primary);">—</span>
                    </div>
                    <div class="summary-row">
                        <span>No. Kamar</span>
                        <span id="summaryRoom" style="font-weight:600;color:var(--text-primary);">—</span>
                    </div>
                    <div class="summary-row">
                        <span>Kategori</span>
                        <span id="summaryKategori" style="font-weight:600;color:var(--text-primary);">—</span>
                    </div>
                    <div class="summary-total-row">
                        <span class="summary-total-label">Total Tagihan</span>
                        <span class="summary-total-amount" id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="action-row">
                    <button type="button" class="btn-cancel" id="btnCancel" onclick="resetForm()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Batal
                    </button>
                    <button type="submit" class="btn-confirm" id="btnConfirm">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        Konfirmasi Transaksi
                    </button>
                </div>

            </div>{{-- card-body --}}
        </div>{{-- card 2 --}}

    </form>

</main>

<script>
/* ─────────────────────────────────────────────────────────
   STATE
───────────────────────────────────────────────────────── */
let selectedGuest = null;  // { id, name, room, checkin }

/* ─────────────────────────────────────────────────────────
   ON LOAD: Restore state if old() has id_reservasi (after validation error)
───────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    const oldId = document.getElementById('hiddenReservasiId').value;
    if (oldId) {
        const row = document.querySelector(`.guest-row[data-id="${oldId}"]`);
        if (row) {
            const name    = row.dataset.name;
            const room    = row.dataset.room;
            const checkin = row.dataset.checkin;
            selectGuest(oldId, name, room, checkin);
        }
    }

    // Auto-dismiss flash after 5s
    const flash = document.getElementById('flashAlert');
    if (flash) {
        setTimeout(() => { flash.style.opacity = '0'; flash.style.transition = 'opacity 0.5s'; setTimeout(() => flash.remove(), 500); }, 5000);
    }
});

/* ─────────────────────────────────────────────────────────
   SELECT GUEST
───────────────────────────────────────────────────────── */
function selectGuest(id, name, room, checkin) {
    selectedGuest = { id, name, room, checkin };

    // Update hidden input
    document.getElementById('hiddenReservasiId').value = id;

    // Update banner
    document.getElementById('bannerName').textContent = name;
    document.getElementById('bannerMeta').textContent = `Kamar ${room} · Check-In: ${formatDate(checkin)}`;
    document.getElementById('guestBanner').classList.add('visible');

    // Highlight row, reset others
    document.querySelectorAll('.guest-row').forEach(r => {
        r.classList.remove('row-selected');
        const btn = r.querySelector('.btn-select-guest');
        btn.classList.remove('selected-state');
        btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Pilih Tamu`;
    });
    const targetRow = document.querySelector(`.guest-row[data-id="${id}"]`);
    if (targetRow) {
        targetRow.classList.add('row-selected');
        const btn = targetRow.querySelector('.btn-select-guest');
        btn.classList.add('selected-state');
        btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Terpilih`;
    }

    // Show billing card
    const billingCard = document.getElementById('billingCard');
    billingCard.classList.add('visible');
    setTimeout(() => billingCard.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);

    // Update summary
    updateSummary();
}

/* ─────────────────────────────────────────────────────────
   CLEAR SELECTION
───────────────────────────────────────────────────────── */
function clearSelection() {
    selectedGuest = null;
    document.getElementById('hiddenReservasiId').value = '';
    document.getElementById('guestBanner').classList.remove('visible');
    document.getElementById('billingCard').classList.remove('visible');

    document.querySelectorAll('.guest-row').forEach(r => {
        r.classList.remove('row-selected');
        const btn = r.querySelector('.btn-select-guest');
        btn.classList.remove('selected-state');
        btn.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Pilih Tamu`;
    });
}

/* ─────────────────────────────────────────────────────────
   RESET FULL FORM
───────────────────────────────────────────────────────── */
function resetForm() {
    clearSelection();
    document.getElementById('deskripsi').value = '';
    document.getElementById('nominal').value = '';
    document.getElementById('kategori').selectedIndex = 0;
    document.getElementById('catatan').value = '';
    updateSummary();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ─────────────────────────────────────────────────────────
   SEARCH / FILTER GUESTS
───────────────────────────────────────────────────────── */
function filterGuests(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('.guest-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const searchVal = row.dataset.search;
        if (!q || searchVal.includes(q)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide no results row
    const noResults = document.getElementById('noResultsRow');
    if (noResults) {
        noResults.classList.toggle('visible', visibleCount === 0 && q !== '');
    }

    // Show/hide clear button
    const clearBtn = document.getElementById('searchClearBtn');
    clearBtn.classList.toggle('visible', q !== '');
}

function clearSearch() {
    const inp = document.getElementById('guestSearchInput');
    inp.value = '';
    filterGuests('');
    inp.focus();
}

/* ─────────────────────────────────────────────────────────
   NOMINAL FORMATTING (thousands separator)
───────────────────────────────────────────────────────── */
function formatNominal(input) {
    let raw = input.value.replace(/\D/g, '');
    if (raw === '') { input.value = ''; updateSummary(); return; }
    input.value = parseInt(raw, 10).toLocaleString('id-ID');
    updateSummary();
}

/* ─────────────────────────────────────────────────────────
   UPDATE SUMMARY PANEL
───────────────────────────────────────────────────────── */
function updateSummary() {
    // Guest & room
    document.getElementById('summaryGuest').textContent = selectedGuest ? selectedGuest.name : '—';
    document.getElementById('summaryRoom').textContent  = selectedGuest ? ('Kamar ' + selectedGuest.room) : '—';

    // Category
    const kategoriEl = document.getElementById('kategori');
    const kategori   = kategoriEl.value;
    document.getElementById('summaryKategori').textContent = kategori || '—';

    // Nominal
    const rawNominal = document.getElementById('nominal').value.replace(/\./g, '').replace(',', '.');
    const parsed     = parseFloat(rawNominal) || 0;
    document.getElementById('summaryTotal').textContent = 'Rp ' + parsed.toLocaleString('id-ID');
}

/* ─────────────────────────────────────────────────────────
   HELPERS
───────────────────────────────────────────────────────── */
function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    if (isNaN(d)) return dateStr;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}
</script>

</body>
</html>
