@extends('layouts.app')

@section('title', 'Kategori Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Kategori Transaksi')
@section('page-subtitle', 'Kelola kategori pemasukan dan pengeluaran')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title">
            <i class="bi bi-tags me-2 text-primary"></i>
            Daftar Kategori
        </h6>
        <a href="{{ route('pengurus.kategori.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jenis</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kat)
                    <tr>
                        <td>{{ $kategoris->firstItem() + $index }}</td>
                        <td>
                            <strong style="font-size:13.5px;">{{ $kat->nama_kategori }}</strong>
                        </td>
                        <td>
                            @if($kat->jenis === 'pemasukan')
                                <span class="badge-pemasukan">Pemasukan</span>
                            @else
                                <span class="badge-pengeluaran">Pengeluaran</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size:12px;color:#718096;">
                                {{ Str::limit($kat->deskripsi, 50) ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($kat->is_active === 'aktif')
                                <span class="badge-aktif">Aktif</span>
                            @else
                                <span class="badge-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pengurus.kategori.edit', $kat->id) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pengurus.kategori.toggle', $kat->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $kat->is_active === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            title="{{ $kat->is_active === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $kat->is_active === 'aktif' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-2 d-block mb-2"></i>
                            Belum ada kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($kategoris->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $kategoris->firstItem() }} - {{ $kategoris->lastItem() }}
            dari {{ $kategoris->total() }} data
        </small>
        {{ $kategoris->links() }}
    </div>
    @endif
</div>

@endsection