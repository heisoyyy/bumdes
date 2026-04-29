{{-- layout/sidebar-masyarakat.blade.php --}}
<div class="sidebar-label">Main Menu</div>

<div class="sidebar-item">
    <a href="{{ route('masyarakat.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('masyarakat.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
</div>  