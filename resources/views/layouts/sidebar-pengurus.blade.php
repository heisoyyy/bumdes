{{-- layout/sidebar-pengurus.blade.php --}}
<div class="sidebar-label">Main Menu</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="sidebar-label mt-2">Keuangan</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.saldo-awal.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.saldo-awal.*') ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i>
        <span>Saldo Awal</span>
    </a>
</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.transaksi.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.transaksi.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right"></i>
        <span>Transaksi</span>
    </a>
</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.kategori.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.kategori.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>
        <span>Kategori</span>
    </a>
</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.laporan.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.laporan.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Laporan</span>
    </a>
</div>

<div class="sidebar-label mt-2">Sistem</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.akun-masyarakat.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.akun-masyarakat.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Akun Masyarakat</span>
    </a>
</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.backup.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.backup.*') ? 'active' : '' }}">
        <i class="bi bi-cloud-arrow-up"></i>
        <span>Backup Data</span>
    </a>
</div>

<div class="sidebar-item">
    <a href="{{ route('pengurus.notifikasi.index') }}"
       class="sidebar-link {{ request()->routeIs('pengurus.notifikasi.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i>
        <span>Notifikasi</span>
        @php
            $notifCount = \App\Models\Notifikasi::where('user_id', auth()->id())->where('is_read', false)->count();
        @endphp
        @if($notifCount > 0)
        <span class="badge bg-danger ms-auto" style="font-size:10px;">{{ $notifCount }}</span>
        @endif
    </a>
</div>