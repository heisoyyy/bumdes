<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BUMDes Kampar Sejahtera')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --secondary: #198754;
            --secondary-dark: #146c43;
            --sidebar-bg: #0f3460;
            --sidebar-hover: #16213e;
            --sidebar-active: #198754;
            --topbar-bg: #ffffff;
            --body-bg: #f0f4f8;
            --card-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            --border-radius: 12px;

            /* Sidebar width variables */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 68px;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            min-height: 100vh;
        }

        /* ========================
           SIDEBAR
        ======================== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: width 0.3s ease;

            display: flex;
            flex-direction: column;

            overflow: hidden;
        }

        /* ---- COLLAPSED STATE ---- */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Brand */
        .sidebar-brand-top {
            padding: 18px 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-brand-top .brand-logo {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #198754, #0d6efd);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .sidebar-brand-top .brand-text {
            transition: opacity 0.15s ease;
        }

        .sidebar.collapsed .sidebar-brand-top .brand-text {
            opacity: 0;
            pointer-events: none;
            width: 0;
        }

        .sidebar-brand-top .brand-name {
            color: white;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
        }

        .sidebar-brand-top .brand-sub {
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
        }

        /* Collapse Toggle Button */
        .sidebar-toggle-btn {
            position: fixed;
            left: calc(var(--sidebar-width) - 14px);
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: #198754;
            border: 2px solid #0f3460;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            transition: left 0.3s ease, background 0.2s ease, transform 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }

        .sidebar.collapsed ~ * .sidebar-toggle-btn,
        .sidebar.collapsed + .main-content .sidebar-toggle-btn {
            left: calc(var(--sidebar-collapsed-width) - 14px);
        }

        /* Since button is inside sidebar, shift when collapsed */
        .sidebar.collapsed .sidebar-toggle-btn {
            left: calc(var(--sidebar-collapsed-width) - 14px);
        }

        .sidebar-toggle-btn:hover {
            background: #146c43;
            transform: translateY(-50%) scale(1.1);
        }

        .sidebar-toggle-btn i {
            color: white;
            font-size: 13px;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .sidebar-toggle-btn i {
            transform: rotate(180deg);
        }

        /* Menu scrollable area */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.35));
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .sidebar-menu:hover::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0.6));
        }

        .sidebar-menu::-webkit-scrollbar-thumb:active {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Section label */
        .sidebar-label {
            color: rgba(255, 255, 255, 0.4);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 10px 20px 5px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .sidebar-label {
            opacity: 0;
            padding-top: 4px;
            padding-bottom: 4px;
            /* Still takes height so items stay in roughly same sections */
        }

        /* Divider line shown in collapsed mode instead of label */
        .sidebar.collapsed .sidebar-label::before {
            content: '';
            display: block;
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 6px 12px 2px;
        }

        .sidebar-item {
            padding: 3px 12px;
            position: relative;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 8px;
            font-size: 13.5px;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-link.active {
            background: var(--sidebar-active);
            color: white;
            font-weight: 500;
        }

        .sidebar-link i {
            font-size: 17px;
            width: 20px;
            flex-shrink: 0;
            text-align: center;
        }

        /* Link text label */
        .sidebar-link .link-text {
            transition: opacity 0.15s ease;
        }

        .sidebar.collapsed .sidebar-link .link-text {
            opacity: 0;
            pointer-events: none;
        }

        /* Tooltip on collapsed */
        .sidebar.collapsed .sidebar-item {
            position: relative;
        }

        .sidebar.collapsed .sidebar-item:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-collapsed-width) - 4px);
            top: 50%;
            transform: translateY(-50%);
            background: #1a1a2e;
            color: white;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            white-space: nowrap;
            z-index: 2000;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border-left: 3px solid #198754;
        }

        .sidebar.collapsed .sidebar-item:hover::before {
            content: '';
            position: absolute;
            left: calc(var(--sidebar-collapsed-width) - 10px);
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1a1a2e;
            z-index: 2000;
            pointer-events: none;
        }

        /* ========================
           SIDEBAR FOOTER
        ======================== */
        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: auto;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            background: rgba(0,0,0,0.12);
            text-align: center;
        }

        .sidebar-footer .footer-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.45);
            font-size: 15px;
        }

        .sidebar-footer .footer-text {
            transition: opacity 0.2s ease, max-height 0.3s ease;
            max-height: 50px;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-footer .footer-text {
            opacity: 0;
            max-height: 0;
            pointer-events: none;
        }

        .sidebar-footer .footer-year {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.4;
        }

        .sidebar-footer .footer-copy {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.3);
        }

        /* ========================
           MAIN CONTENT
        ======================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ========================
           TOPBAR
        ======================== */
        .topbar {
            background: var(--topbar-bg);
            padding: 12px 25px;
            border-bottom: 1px solid #e8ecf0;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .topbar .page-title {
            font-size: 17px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .topbar .page-subtitle {
            font-size: 12px;
            color: #718096;
            margin: 0;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ========================
           CONTENT AREA
        ======================== */
        .content-area {
            padding: 25px;
        }

        /* ========================
           CARDS
        ======================== */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e8ecf0;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 15px 20px;
        }

        .card-header .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        /* ========================
           STAT CARDS
        ======================== */
        .stat-card {
            border-radius: var(--border-radius);
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-value {
            font-size: 22px;
            font-weight: 700;
            margin: 8px 0 3px;
        }

        .stat-card .stat-label {
            font-size: 12px;
            opacity: 0.85;
        }

        .stat-card .stat-sub {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
        }

        .bg-gradient-green {
            background: linear-gradient(135deg, #198754, #146c43);
        }

        .bg-gradient-teal {
            background: linear-gradient(135deg, #0dcaf0, #0a9fbf);
        }

        .bg-gradient-orange {
            background: linear-gradient(135deg, #fd7e14, #e8670e);
        }

        /* ========================
           TABLES
        ======================== */
        .table th {
            background: #f8fafc;
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e8ecf0;
            padding: 12px 15px;
        }

        .table td {
            padding: 12px 15px;
            font-size: 13.5px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f4f8;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* ========================
           BADGES
        ======================== */
        .badge-pemasukan {
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-pengeluaran {
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-aktif {
            background: #d1fae5;
            color: #065f46;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-nonaktif {
            background: #fee2e2;
            color: #991b1b;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* ========================
           BUTTONS
        ======================== */
        .btn {
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754, #146c43);
            border: none;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* ========================
           FORMS
        ======================== */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 13.5px;
            padding: 9px 14px;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 6px;
        }

        /* ========================
           ALERTS
        ======================== */
        .alert {
            border-radius: 10px;
            border: none;
            font-size: 13.5px;
        }

        /* ========================
           PAGINATION
        ======================== */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: 13px;
            color: #0d6efd;
            border: 1.5px solid #e2e8f0;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-color: transparent;
        }

        /* ========================
           RESPONSIVE
        ======================== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Hide collapse button on mobile */
            .sidebar-toggle-btn {
                display: none;
            }

            .main-content,
            .main-content.collapsed {
                margin-left: 0;
            }

            .content-area {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <!-- Collapse Toggle Button (tengah sidebar) -->
        <button class="sidebar-toggle-btn" id="sidebarCollapseBtn" title="Sembunyikan sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>

        <!-- Brand -->
        <a href="#" class="sidebar-brand-top">
            <div class="brand-logo">
                <i class="bi bi-bank"></i>
            </div>
            <div class="brand-text">
                <div class="brand-name">BUMDes Kampar</div>
                <div class="brand-sub">Sejahtera</div>
            </div>
        </a>

        <!-- Menu -->
        <div class="sidebar-menu">
            @yield('sidebar-menu')
        </div>

        <!-- Footer -->
        <div class="sidebar-footer">
            <div class="footer-icon">
                <i class="bi bi-bank2"></i>
            </div>
            <div class="footer-text">
                <div class="footer-year">© {{ date('Y') }} BUMDes Kampar</div>
                <div class="footer-copy">Nat · All rights reserved</div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content" id="mainContent">

        <!-- TOPBAR -->
        <div class="topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile hamburger -->
                <button class="btn btn-sm d-md-none" id="sidebarToggle" style="background:#f0f4f8;">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <p class="page-title">@yield('page-title', 'Dashboard')</p>
                    <p class="page-subtitle">@yield('page-subtitle', 'BUMDes Kampar Sejahtera')</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">

                {{-- NOTIFIKASI (hanya pengurus) --}}
                @if (auth()->user()->role === 'pengurus')
                    @php
                        $notifCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    <div class="dropdown">
                        <button class="btn position-relative p-2"
                            style="background:#f0f4f8;border-radius:10px;border:none;" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5" style="color:#4a5568;"></i>
                            @if ($notifCount > 0)
                                <span class="position-absolute"
                                    style="top:-4px;right:-4px;background:#dc3545;color:white;
                             font-size:10px;width:18px;height:18px;border-radius:50%;
                             display:flex;align-items:center;justify-content:center;
                             font-weight:600;border:2px solid white;">
                                    {{ $notifCount > 9 ? '9+' : $notifCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div class="dropdown-menu dropdown-menu-end shadow-lg"
                            style="width:340px;border:none;border-radius:14px;
                        padding:0;overflow:hidden;">

                            {{-- Header --}}
                            <div
                                style="padding:14px 18px;background:linear-gradient(135deg,#0d6efd,#198754);
                            display:flex;align-items:center;justify-content:space-between;">
                                <div>
                                    <div style="color:white;font-size:14px;font-weight:600;">
                                        Notifikasi
                                    </div>
                                    <div style="color:rgba(255,255,255,0.7);font-size:11px;">
                                        {{ $notifCount }} belum dibaca
                                    </div>
                                </div>
                                @if ($notifCount > 0)
                                    <form action="{{ route('pengurus.notifikasi.read.all') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            style="background:rgba(255,255,255,0.2);
                                       color:white;border:none;border-radius:8px;
                                       padding:5px 10px;font-size:11px;cursor:pointer;">
                                            <i class="bi bi-check-all me-1"></i>Baca Semua
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- List Notifikasi --}}
                            <div style="max-height:300px;overflow-y:auto;">
                                @php
                                    $notifList = \App\Models\Notifikasi::where('user_id', auth()->id())
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp

                                @forelse($notifList as $notif)
                                    <div style="padding:12px 18px;border-bottom:1px solid #f0f4f8;
                                background:{{ !$notif->is_read ? '#f0f7ff' : 'white' }};
                                transition:background 0.2s;"
                                        onmouseover="this.style.background='#f8fafc'"
                                        onmouseout="this.style.background='{{ !$notif->is_read ? '#f0f7ff' : 'white' }}'">
                                        <div class="d-flex align-items-start gap-3">
                                            <div
                                                style="width:36px;height:36px;flex-shrink:0;
                                        background:{{ !$notif->is_read ? 'linear-gradient(135deg,#0d6efd,#198754)' : '#f0f4f8' }};
                                        border-radius:9px;display:flex;
                                        align-items:center;justify-content:center;">
                                                <i class="bi bi-bell"
                                                    style="font-size:14px;
                                          color:{{ !$notif->is_read ? 'white' : '#718096' }};"></i>
                                            </div>
                                            <div style="flex:1;min-width:0;">
                                                <div
                                                    style="font-size:13px;
                                            font-weight:{{ !$notif->is_read ? '600' : '400' }};
                                            color:#2d3748;
                                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    {{ $notif->judul }}
                                                </div>
                                                <div
                                                    style="font-size:12px;color:#718096;margin-top:2px;
                                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    {{ $notif->pesan }}
                                                </div>
                                                <div style="font-size:10px;color:#a0aec0;margin-top:3px;">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            @if (!$notif->is_read)
                                                <div
                                                    style="width:8px;height:8px;background:#0d6efd;
                                        border-radius:50%;flex-shrink:0;margin-top:4px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div style="padding:30px;text-align:center;">
                                        <i class="bi bi-bell-slash"
                                            style="font-size:32px;color:#cbd5e0;display:block;margin-bottom:8px;"></i>
                                        <div style="font-size:13px;color:#a0aec0;">
                                            Tidak ada notifikasi
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer --}}
                            <div
                                style="padding:10px 18px;border-top:1px solid #f0f4f8;
                            text-align:center;background:#fafafa;">
                                <a href="{{ route('pengurus.notifikasi.index') }}"
                                    style="font-size:12px;color:#0d6efd;text-decoration:none;font-weight:500;">
                                    <i class="bi bi-eye me-1"></i>Lihat Semua Notifikasi
                                </a>
                            </div>

                        </div>
                    </div>
                @endif

                {{-- PROFIL DROPDOWN --}}
                <div class="dropdown">
                    <button class="btn d-flex align-items-center gap-2 p-2"
                        style="background:#f0f4f8;border-radius:10px;border:none;" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- Avatar --}}
                        @if (auth()->user()->foto_profil)
                            <img src="{{ auth()->user()->foto_profil_url }}"
                                style="width:32px;height:32px;border-radius:8px;object-fit:cover;">
                        @else
                            <div
                                style="width:32px;height:32px;
                                background:linear-gradient(135deg,#198754,#0d6efd);
                                border-radius:8px;display:flex;align-items:center;
                                justify-content:center;color:white;
                                font-size:14px;font-weight:600;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="d-none d-md-block text-start">
                            <div style="font-size:13px;font-weight:500;color:#2d3748;line-height:1.2;">
                                {{ Str::limit(auth()->user()->name, 15) }}
                            </div>
                            <div style="font-size:11px;color:#718096;line-height:1.2;">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down d-none d-md-block" style="font-size:11px;color:#718096;"></i>
                    </button>

                    {{-- Dropdown Profil --}}
                    <div class="dropdown-menu dropdown-menu-end shadow-lg"
                        style="width:230px;border:none;border-radius:14px;
                        padding:0;overflow:hidden;">

                        {{-- Header Profil --}}
                        <div
                            style="padding:16px 18px;
                            background:linear-gradient(135deg,#0f3460,#16213e);
                            text-align:center;">
                            @if (auth()->user()->foto_profil)
                                <img src="{{ auth()->user()->foto_profil_url }}"
                                    style="width:55px;height:55px;border-radius:50%;
                                    object-fit:cover;border:2px solid rgba(255,255,255,0.3);
                                    margin-bottom:8px;">
                            @else
                                <div
                                    style="width:55px;height:55px;border-radius:50%;
                                    background:linear-gradient(135deg,#198754,#0d6efd);
                                    display:flex;align-items:center;justify-content:center;
                                    color:white;font-size:22px;font-weight:700;
                                    margin:0 auto 8px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div style="color:white;font-size:14px;font-weight:600;">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="color:rgba(255,255,255,0.6);font-size:11px;margin-top:2px;">
                                {{ auth()->user()->email }}
                            </div>
                            <span
                                style="background:rgba(255,255,255,0.15);color:white;
                                 font-size:10px;padding:3px 10px;border-radius:20px;
                                 display:inline-block;margin-top:5px;">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                            </span>
                        </div>

                        {{-- Menu --}}
                        <div style="padding:8px 0;">
                            {{-- Kelola Akun --}}
                            @php
                                $akunRoute = match (auth()->user()->role) {
                                    'pengurus' => route('pengurus.akun.index'),
                                    'kepala_desa' => route('kepaladesa.akun.index'),
                                    'masyarakat' => route('masyarakat.akun.index'),
                                    default => '#',
                                };
                            @endphp
                            <a href="{{ $akunRoute }}" class="dropdown-item d-flex align-items-center gap-3"
                                style="padding:10px 18px;font-size:13.5px;color:#2d3748;">
                                <div
                                    style="width:32px;height:32px;background:#eff6ff;
                                    border-radius:8px;display:flex;align-items:center;
                                    justify-content:center;">
                                    <i class="bi bi-person-gear" style="color:#0d6efd;font-size:14px;"></i>
                                </div>
                                <span>Kelola Akun</span>
                            </a>

                            {{-- Ganti Password --}}
                            <a href="{{ $akunRoute }}?tab=password"
                                class="dropdown-item d-flex align-items-center gap-3"
                                style="padding:10px 18px;font-size:13.5px;color:#2d3748;">
                                <div
                                    style="width:32px;height:32px;background:#fefce8;
                                    border-radius:8px;display:flex;align-items:center;
                                    justify-content:center;">
                                    <i class="bi bi-shield-lock" style="color:#ca8a04;font-size:14px;"></i>
                                </div>
                                <span>Ganti Password</span>
                            </a>

                            @if (auth()->user()->role === 'pengurus')
                                {{-- Notifikasi --}}
                                <a href="{{ route('pengurus.notifikasi.index') }}"
                                    class="dropdown-item d-flex align-items-center gap-3"
                                    style="padding:10px 18px;font-size:13.5px;color:#2d3748;">
                                    <div
                                        style="width:32px;height:32px;background:#f0fdf4;
                                    border-radius:8px;display:flex;align-items:center;
                                    justify-content:center;">
                                        <i class="bi bi-bell" style="color:#198754;font-size:14px;"></i>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>Notifikasi</span>
                                        @if ($notifCount > 0)
                                            <span
                                                style="background:#dc3545;color:white;font-size:10px;
                                         padding:1px 7px;border-radius:20px;font-weight:600;">
                                                {{ $notifCount }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endif

                            <hr style="margin:6px 0;border-color:#f0f4f8;">

                            {{-- Logout --}}
                            <form action="{{ route('logout') }}" method="POST" style="padding:0 8px 6px;">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-3"
                                    style="padding:10px 10px;font-size:13.5px;
                                       color:#dc3545;border-radius:8px;">
                                    <div
                                        style="width:32px;height:32px;background:#fff5f5;
                                        border-radius:8px;display:flex;align-items:center;
                                        justify-content:center;">
                                        <i class="bi bi-box-arrow-left" style="color:#dc3545;font-size:14px;"></i>
                                    </div>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- ALERTS -->
        <div class="px-4 pt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"
                    role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2"
                    role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- CONTENT -->
        <div class="content-area">
            @yield('content')
        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ========================
        // SIDEBAR COLLAPSE (desktop)
        // ========================
        const sidebar      = document.getElementById('sidebar');
        const mainContent  = document.getElementById('mainContent');
        const collapseBtn  = document.getElementById('sidebarCollapseBtn');
        const STORAGE_KEY  = 'sidebar_collapsed';

        function applySidebarState(collapsed, animate) {
            if (!animate) {
                sidebar.style.transition     = 'none';
                mainContent.style.transition = 'none';
                if (collapseBtn) collapseBtn.style.transition = 'none';
            }

            if (collapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                if (collapseBtn) collapseBtn.style.left = 'calc(var(--sidebar-collapsed-width) - 14px)';
                if (collapseBtn) collapseBtn.title = 'Tampilkan sidebar';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('collapsed');
                if (collapseBtn) collapseBtn.style.left = 'calc(var(--sidebar-width) - 14px)';
                if (collapseBtn) collapseBtn.title = 'Sembunyikan sidebar';
            }

            if (!animate) {
                requestAnimationFrame(() => {
                    sidebar.style.transition     = '';
                    mainContent.style.transition = '';
                    if (collapseBtn) collapseBtn.style.transition = '';
                });
            }
        }

        // Restore saved state (no animation on page load)
        const savedCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
        applySidebarState(savedCollapsed, false);

        // Toggle on button click
        collapseBtn?.addEventListener('click', function () {
            const isNowCollapsed = !sidebar.classList.contains('collapsed');
            applySidebarState(isNowCollapsed, true);
            localStorage.setItem(STORAGE_KEY, isNowCollapsed);
        });

        // ========================
        // SIDEBAR TOGGLE (mobile)
        // ========================
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });

        // ========================
        // AUTO HIDE ALERTS
        // ========================
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (el) {
                let alert = bootstrap.Alert.getOrCreateInstance(el);
                alert.close();
            });
        }, 4000);
    </script>

    @stack('scripts')
</body>

</html>