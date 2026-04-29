@extends('layouts.app')

@section('title', 'Log Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-kepaladesa')
@endsection

@section('page-title', 'Log Transaksi')
@section('page-subtitle', 'Riwayat semua aktivitas transaksi')

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('kepaladesa.log-transaksi.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari Nama Pengurus</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Nama pengurus..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Aksi</label>
                    <select name="aksi" class="form-select">
                        <option value="">Semua Aksi</option>
                        <option value="tambah" {{ request('aksi') === 'tambah' ? 'selected' : '' }}>Tambah</option>
                        <option value="edit"   {{ request('aksi') === 'edit'   ? 'selected' : '' }}>Edit</option>
                        <option value="hapus"  {{ request('aksi') === 'hapus'  ? 'selected' : '' }}>Hapus</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control"
                           value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('kepaladesa.log-transaksi.index') }}"
                       class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h6 class="card-title">
            <i class="bi bi-journal-text me-2 text-primary"></i>
            Log Aktivitas Transaksi
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Pengurus</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td>
                            @if($log->transaksi)
                                <code style="font-size:12px;">{{ $log->transaksi->kode_transaksi }}</code>
                            @else
                                <span class="text-muted" style="font-size:12px;">-</span>
                            @endif
                        </td>
                        <td>{{ $log->user->name ?? '-' }}</td>
                        <td>
                            @if($log->aksi === 'tambah')
                                <span class="badge bg-success" style="font-size:11px;">
                                    <i class="bi bi-plus me-1"></i>Tambah
                                </span>
                            @elseif($log->aksi === 'edit')
                                <span class="badge bg-warning text-dark" style="font-size:11px;">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </span>
                            @else
                                <span class="badge bg-danger" style="font-size:11px;">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size:12px;color:#718096;">
                                {{ Str::limit($log->keterangan, 40) ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size:12px;color:#718096;">
                                {{ $log->ip_address ?? '-' }}
                            </span>
                        </td>
                        <td style="font-size:12px;">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('kepaladesa.log-transaksi.show', $log->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                            Belum ada log transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }}
            dari {{ $logs->total() }} data
        </small>
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection