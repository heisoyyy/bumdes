@extends('layouts.app')

@section('title', 'Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Transaksi')
@section('page-subtitle', 'Kelola data transaksi keuangan')

@section('content')

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pengurus.transaksi.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Kode / keterangan..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua</option>
                        <option value="pemasukan"   {{ request('jenis') === 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ request('jenis') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                        @endforeach
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
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title">
            <i class="bi bi-arrow-left-right me-2 text-primary"></i>
            Daftar Transaksi
        </h6>
        <a href="{{ route('pengurus.transaksi.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Transaksi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Saldo Setelah</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $index => $trx)
                    <tr>
                        <td>{{ $transaksi->firstItem() + $index }}</td>
                        <td><code style="font-size:12px;">{{ $trx->kode_transaksi }}</code></td>
                        <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $trx->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            @if($trx->jenis === 'pemasukan')
                                <span class="badge-pemasukan">
                                    <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                </span>
                            @else
                                <span class="badge-pengeluaran">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong class="{{ $trx->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $trx->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>Rp {{ number_format($trx->saldo_setelah, 0, ',', '.') }}</td>
                        <td>
                            <span style="font-size:12px;color:#718096;">
                                {{ Str::limit($trx->keterangan, 30) ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pengurus.transaksi.show', $trx->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('pengurus.transaksi.edit', $trx->id) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pengurus.transaksi.destroy', $trx->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksi->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }}
            dari {{ $transaksi->total() }} data
        </small>
        {{ $transaksi->links() }}
    </div>
    @endif
</div>

@endsection