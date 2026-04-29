@extends('layouts.app')

@section('title', 'Detail Log Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-kepaladesa')
@endsection

@section('page-title', 'Detail Log Transaksi')
@section('page-subtitle', 'Informasi lengkap perubahan transaksi')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title">
                    <i class="bi bi-journal-text me-2 text-primary"></i>
                    Detail Log
                </h6>
                <a href="{{ route('kepaladesa.log-transaksi.index') }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                {{-- Info Log --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;">
                                Aksi
                            </div>
                            <div style="margin-top:5px;">
                                @if($log->aksi === 'tambah')
                                    <span class="badge bg-success">Tambah</span>
                                @elseif($log->aksi === 'edit')
                                    <span class="badge bg-warning text-dark">Edit</span>
                                @else
                                    <span class="badge bg-danger">Hapus</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;">
                                Dilakukan Oleh
                            </div>
                            <div style="font-size:14px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $log->user->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;">
                                Waktu
                            </div>
                            <div style="font-size:14px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="background:#f8fafc;border-radius:10px;padding:15px;">
                            <div style="font-size:11px;color:#718096;font-weight:600;text-transform:uppercase;">
                                IP Address
                            </div>
                            <div style="font-size:14px;font-weight:500;color:#2d3748;margin-top:5px;">
                                {{ $log->ip_address ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Perubahan --}}
                @if($log->aksi === 'edit' && $log->data_lama && $log->data_baru)
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0" style="background:#fff8f8;">
                            <div class="card-header" style="background:#fff8f8;border-bottom:1px solid #fecaca;">
                                <h6 style="font-size:13px;font-weight:600;color:#991b1b;margin:0;">
                                    <i class="bi bi-x-circle me-1"></i>Data Sebelum
                                </h6>
                            </div>
                            <div class="card-body">
                                <pre style="font-size:12px;color:#4a5568;background:transparent;border:none;padding:0;margin:0;white-space:pre-wrap;">{{ json_encode($log->data_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0" style="background:#f0fdf4;">
                            <div class="card-header" style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;">
                                <h6 style="font-size:13px;font-weight:600;color:#065f46;margin:0;">
                                    <i class="bi bi-check-circle me-1"></i>Data Sesudah
                                </h6>
                            </div>
                            <div class="card-body">
                                <pre style="font-size:12px;color:#4a5568;background:transparent;border:none;padding:0;margin:0;white-space:pre-wrap;">{{ json_encode($log->data_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($log->aksi === 'tambah' && $log->data_baru)
                <div class="card border-0" style="background:#f0fdf4;">
                    <div class="card-header" style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;">
                        <h6 style="font-size:13px;font-weight:600;color:#065f46;margin:0;">
                            <i class="bi bi-plus-circle me-1"></i>Data Ditambahkan
                        </h6>
                    </div>
                    <div class="card-body">
                        <pre style="font-size:12px;color:#4a5568;background:transparent;border:none;padding:0;margin:0;white-space:pre-wrap;">{{ json_encode($log->data_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>

                @elseif($log->aksi === 'hapus' && $log->data_lama)
                <div class="card border-0" style="background:#fff8f8;">
                    <div class="card-header" style="background:#fff8f8;border-bottom:1px solid #fecaca;">
                        <h6 style="font-size:13px;font-weight:600;color:#991b1b;margin:0;">
                            <i class="bi bi-trash me-1"></i>Data Dihapus
                        </h6>
                    </div>
                    <div class="card-body">
                        <pre style="font-size:12px;color:#4a5568;background:transparent;border:none;padding:0;margin:0;white-space:pre-wrap;">{{ json_encode($log->data_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection