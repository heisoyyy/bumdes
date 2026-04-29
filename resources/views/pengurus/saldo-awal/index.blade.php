@extends('layouts.app')

@section('title', 'Saldo Awal')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Saldo Awal')
@section('page-subtitle', 'Kelola saldo awal BUMDes')

@section('content')

{{-- Total Saldo Awal --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-gradient-blue">
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">Rp {{ number_format($totalSaldoAwal, 0, ',', '.') }}</div>
            <div class="stat-label">Total Saldo Awal</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title">
            <i class="bi bi-wallet2 me-2 text-primary"></i>
            Daftar Saldo Awal
        </h6>
        <a href="{{ route('pengurus.saldo-awal.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Saldo Awal
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($saldoAwal as $index => $saldo)
                    <tr>
                        <td>{{ $saldoAwal->firstItem() + $index }}</td>
                        <td>{{ $saldo->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <strong class="text-primary">
                                Rp {{ number_format($saldo->nominal, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            <span style="font-size:12px;color:#718096;">
                                {{ Str::limit($saldo->keterangan, 40) ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $saldo->user->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pengurus.saldo-awal.edit', $saldo->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-2 d-block mb-2"></i>
                            Belum ada saldo awal
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($saldoAwal->hasPages())
    <div class="card-footer">
        {{ $saldoAwal->links() }}
    </div>
    @endif
</div>

@endsection