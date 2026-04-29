@extends('layouts.app')

@section('title', 'Backup Data')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Backup Data')
@section('page-subtitle', 'Kelola backup dan restore data')

@section('content')

<div class="row g-3">
    {{-- Tombol Backup --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>
                    Buat Backup Baru
                </h6>
            </div>
            <div class="card-body">
                <p style="font-size:13px;color:#718096;">
                    Backup akan menyimpan semua data sistem ke dalam file JSON
                    yang dapat digunakan untuk restore.
                </p>
                <form action="{{ route('pengurus.backup.store') }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin membuat backup sekarang?')">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        Buat Backup Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Backup --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-archive me-2 text-primary"></i>
                    Riwayat Backup
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $index => $backup)
                            <tr>
                                <td>{{ $backups->firstItem() + $index }}</td>
                                <td>
                                    <span style="font-size:12px;">{{ $backup->nama_file }}</span>
                                </td>
                                <td>{{ $backup->ukuran_file }}</td>
                                <td>
                                    <span class="badge {{ $backup->tipe_backup === 'manual' ? 'bg-primary' : 'bg-info' }}"
                                          style="font-size:11px;">
                                        {{ ucfirst($backup->tipe_backup) }}
                                    </span>
                                </td>
                                <td>
                                    @if($backup->status === 'berhasil')
                                        <span class="badge-aktif">Berhasil</span>
                                    @else
                                        <span class="badge-nonaktif">Gagal</span>
                                    @endif
                                </td>
                                <td style="font-size:12px;">
                                    {{ $backup->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($backup->status === 'berhasil')
                                        <form action="{{ route('pengurus.backup.restore', $backup->id) }}"
                                              method="GET"
                                              onsubmit="return confirm('Yakin restore dari backup ini? Data saat ini akan digantikan.')">
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('pengurus.backup.destroy', $backup->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus backup ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-cloud-slash fs-2 d-block mb-2"></i>
                                    Belum ada backup
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($backups->hasPages())
            <div class="card-footer">
                {{ $backups->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection