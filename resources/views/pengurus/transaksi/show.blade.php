@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Detail Transaksi')
@section('page-subtitle', 'Informasi lengkap transaksi')

@section('content')

<div class="row g-3">
    {{-- Detail Transaksi --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Detail Transaksi
                </h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('pengurus.transaksi.edit', $transaksi->id) }}"
                       class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('pengurus.transaksi.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Kode Transaksi
                            </div>
                            <div style="font-size:16px;font-weight:600;color:#2d3748;margin-top:5px;">
                                <code>{{ $transaksi->kode_transaksi }}</code>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Jenis Transaksi
                            </div>
                            <div style="margin-top:5px;">
                                @if($transaksi->jenis === 'pemasukan')
                                    <span class="badge-pemasukan">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                    </span>
                                @else
                                    <span class="badge-pengeluaran">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Nominal
                            </div>
                            <div style="font-size:20px;font-weight:700;margin-top:5px;"
                                 class="{{ $transaksi->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $transaksi->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Saldo Setelah Transaksi
                            </div>
                            <div style="font-size:20px;font-weight:700;color:#0d6efd;margin-top:5px;">
                                Rp {{ number_format($transaksi->saldo_setelah, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Kategori
                            </div>
                            <div style="font-size:15px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $transaksi->kategori->nama_kategori ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Tanggal
                            </div>
                            <div style="font-size:15px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $transaksi->tanggal->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Dicatat Oleh
                            </div>
                            <div style="font-size:15px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $transaksi->user->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Waktu Input
                            </div>
                            <div style="font-size:15px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $transaksi->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;">
                                Keterangan
                            </div>
                            <div style="font-size:14px;color:#4a5568;margin-top:5px;">
                                {{ $transaksi->keterangan ?? 'Tidak ada keterangan' }}
                            </div>
                        </div>
                    </div>

                    {{-- Bukti Transaksi --}}
                    @if($transaksi->bukti_transaksi)
                    <div class="col-12">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
                                Bukti Transaksi
                            </div>
                            <img src="{{ $transaksi->bukti_transaksi_url }}"
                                 alt="Bukti Transaksi"
                                 style="max-width:300px;border-radius:10px;border:1px solid #e2e8f0;">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Log Transaksi --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-journal-text me-2 text-warning"></i>
                    Riwayat Perubahan
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($transaksi->logTransaksi as $log)
                <div style="padding:15px;border-bottom:1px solid #f0f4f8;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        @if($log->aksi === 'tambah')
                            <span class="badge bg-success" style="font-size:11px;">Tambah</span>
                        @elseif($log->aksi === 'edit')
                            <span class="badge bg-warning text-dark" style="font-size:11px;">Edit</span>
                        @else
                            <span class="badge bg-danger" style="font-size:11px;">Hapus</span>
                        @endif
                        <span style="font-size:12px;color:#718096;">
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div style="font-size:13px;color:#4a5568;">
                        {{ $log->user->name ?? '-' }}
                    </div>
                    @if($log->keterangan)
                    <div style="font-size:12px;color:#718096;">
                        {{ $log->keterangan }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                    Belum ada riwayat
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection