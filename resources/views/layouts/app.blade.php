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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:        #0d6efd;
            --primary-dark:   #0a58ca;
            --secondary:      #198754;
            --secondary-dark: #146c43;
            --sidebar-bg:     #0f3460;
            --sidebar-hover:  #16213e;
            --sidebar-active: #198754;
            --topbar-bg:      #ffffff;
            --body-bg:        #f0f4f8;
            --card-shadow:    0 2px 15px rgba(0,0,0,0.08);
            --border-radius:  12px;
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
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;

            display: flex;
            flex-direction: column;

            overflow: hidden; /* penting: biar yang scroll cuma menu */
        }

        .sidebar-brand {
            padding: 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
        }

        .sidebar-brand .brand-logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #198754, #0d6efd);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar-brand .brand-name {
            color: white;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
        }

        .sidebar-brand .brand-sub {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin; /* Firefox */
            scrollbar-color: rgba(255,255,255,0.25) transparent;
        }

        /* Chrome, Edge, Safari */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255,255,255,0.15), rgba(255,255,255,0.35));
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        /* Hover effect */
        .sidebar-menu:hover::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255,255,255,0.35), rgba(255,255,255,0.6));
        }

        /* Saat di-drag */
        .sidebar-menu::-webkit-scrollbar-thumb:active {
            background: rgba(255,255,255,0.8);
        }

        .sidebar-label {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 10px 20px 5px;
        }

        .sidebar-item {
            padding: 3px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 8px;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.1);
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
            text-align: center;
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }

        /* ========================
           MAIN CONTENT
        ======================== */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
            background: rgba(255,255,255,0.2);
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
            background: rgba(255,255,255,0.08);
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
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
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
        <!-- Brand -->
        <a href="#" class="sidebar-brand d-flex align-items-center gap-3 text-decoration-none">
            <div class="brand-logo">
                <i class="bi bi-bank"></i>
            </div>
            <div>
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
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:35px;height:35px;background:linear-gradient(135deg,#198754,#0d6efd);border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div style="color:white;font-size:12px;font-weight:500;">{{ auth()->user()->name }}</div>
                    <div style="color:rgba(255,255,255,0.5);font-size:11px;">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,0.1);color:white;border:1px solid rgba(255,255,255,0.2);">
                    <i class="bi bi-box-arrow-left me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content" id="mainContent">

        <!-- TOPBAR -->
        <div class="topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm d-md-none" id="sidebarToggle" style="background:#f0f4f8;">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <p class="page-title">@yield('page-title', 'Dashboard')</p>
                    <p class="page-subtitle">@yield('page-subtitle', 'BUMDes Kampar Sejahtera')</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(auth()->user()->role === 'pengurus')
                <a href="{{ route('pengurus.notifikasi.index') }}" class="position-relative text-decoration-none">
                    <i class="bi bi-bell fs-5 text-secondary"></i>
                    @if(isset($notifikasiCount) && $notifikasiCount > 0)
                    <span class="notif-badge">{{ $notifikasiCount > 9 ? '9+' : $notifikasiCount }}</span>
                    @endif
                </a>
                @endif
                <div class="d-flex align-items-center gap-2">
                    <div style="width:35px;height:35px;background:linear-gradient(135deg,#198754,#0d6efd);border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:600;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block">
                        <div style="font-size:13px;font-weight:500;color:#2d3748;">{{ auth()->user()->name }}</div>
                        <div style="font-size:11px;color:#718096;">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTS -->
        <div class="px-4 pt-3">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
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
        // Sidebar toggle mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto hide alerts
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