<div class="sidebar-label">Main Menu</div>

<div class="sidebar-item">
    <a href="{{ route('kepaladesa.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('kepaladesa.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="sidebar-label mt-2">Monitoring</div>

<div class="sidebar-item">
    <a href="{{ route('kepaladesa.log-transaksi.index') }}"
       class="sidebar-link {{ request()->routeIs('kepaladesa.log-transaksi.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i>
        <span>Log Transaksi</span>
    </a>
</div>