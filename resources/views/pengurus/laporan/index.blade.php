@extends('layouts.app')

@section('title', 'Laporan')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Laporan Keuangan')
@section('page-subtitle', 'Generate dan download laporan')

@section('content')

<div class="row g-3">
    {{-- Generate Laporan --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-file-earmark-plus me-2 text-success"></i>
                    Generate Laporan Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.laporan.generate') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="periode_bulan"
                                    class="form-select @error('periode_bulan') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Bulan</option>
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                                <option value="{{ $num }}" {{ old('periode_bulan') == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('periode_bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <select name="periode_tahun"
                                    class="form-select @error('periode_tahun') is-invalid @enderror"
                                    required>
                                @for($y = date('Y'); $y >= 2019; $y--)
                                <option value="{{ $y }}" {{ old('periode_tahun', date('Y')) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                                @endfor
                            </select>
                            @error('periode_tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i>
                        Generate Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Laporan --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-archive me-2 text-primary"></i>
                    Riwayat Laporan
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal Generate</th>
                                <th>Download</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $index => $lap)
                            <tr>
                                <td>{{ $laporans->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $lap->nama_bulan }} {{ $lap->periode_tahun }}</strong>
                                </td>
                                <td>{{ $lap->user->name ?? '-' }}</td>
                                <td>{{ $lap->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($lap->file_pdf)
                                        <a href="{{ route('pengurus.laporan.download.pdf', $lap->id) }}"
                                           class="btn btn-sm btn-outline-danger" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                        @endif
                                        @if($lap->file_excel)
                                        <a href="{{ route('pengurus.laporan.download.excel', $lap->id) }}"
                                           class="btn btn-sm btn-outline-success" title="Download Excel">
                                            <i class="bi bi-file-earmark-excel"></i> Excel
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('pengurus.laporan.destroy', $lap->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
                                    Belum ada laporan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($laporans->hasPages())
            <div class="card-footer">
                {{ $laporans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection