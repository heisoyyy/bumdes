@extends('layouts.app')

@section('title', 'Akun Masyarakat')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Akun Masyarakat')
@section('page-subtitle', 'Kelola akun masyarakat desa')

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pengurus.akun-masyarakat.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Nama / username / email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title">
            <i class="bi bi-people me-2 text-primary"></i>
            Daftar Akun Masyarakat
        </h6>
        <a href="{{ route('pengurus.akun-masyarakat.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Akun
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($masyarakat as $index => $user)
                    <tr>
                        <td>{{ $masyarakat->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#0d6efd,#198754);
                                            border-radius:8px;display:flex;align-items:center;
                                            justify-content:center;color:white;font-size:13px;font-weight:600;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong style="font-size:13.5px;">{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->no_hp ?? '-' }}</td>
                        <td>
                            @if($user->is_active === 'aktif')
                                <span class="badge-aktif">Aktif</span>
                            @else
                                <span class="badge-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pengurus.akun-masyarakat.edit', $user->id) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pengurus.akun-masyarakat.toggle', $user->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $user->is_active === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            title="{{ $user->is_active === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $user->is_active === 'aktif' ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            Belum ada akun masyarakat
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($masyarakat->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $masyarakat->firstItem() }} - {{ $masyarakat->lastItem() }}
            dari {{ $masyarakat->total() }} data
        </small>
        {{ $masyarakat->links() }}
    </div>
    @endif
</div>

@endsection