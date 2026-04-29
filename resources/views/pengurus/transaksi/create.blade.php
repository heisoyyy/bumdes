@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Tambah Transaksi')
@section('page-subtitle', 'Input transaksi baru')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Form Tambah Transaksi
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.transaksi.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        {{-- Jenis Transaksi --}}
                        <div class="col-12">
                            <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="jenis" id="pemasukan" value="pemasukan"
                                           {{ old('jenis') === 'pemasukan' ? 'checked' : '' }}
                                           onchange="filterKategori('pemasukan')" required>
                                    <label class="form-check-label text-success fw-500" for="pemasukan">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="jenis" id="pengeluaran" value="pengeluaran"
                                           {{ old('jenis') === 'pengeluaran' ? 'checked' : '' }}
                                           onchange="filterKategori('pengeluaran')">
                                    <label class="form-check-label text-danger fw-500" for="pengeluaran">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                    </label>
                                </div>
                            </div>
                            @error('jenis')
                            <div class="text-danger" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}"
                                        data-jenis="{{ $kat->jenis }}"
                                        {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nominal --}}
                        <div class="col-md-6">
                            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="nominal"
                                   class="form-control @error('nominal') is-invalid @enderror"
                                   placeholder="Contoh: 1000000"
                                   value="{{ old('nominal') }}"
                                   min="1"
                                   required>
                            @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   required>
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bukti Transaksi --}}
                        <div class="col-md-6">
                            <label class="form-label">Bukti Transaksi</label>
                            <input type="file"
                                   name="bukti_transaksi"
                                   class="form-control @error('bukti_transaksi') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg">
                            <div style="font-size:11px;color:#718096;margin-top:4px;">
                                Format: JPG, PNG. Maksimal 2MB
                            </div>
                            @error('bukti_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Keterangan transaksi (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Simpan Transaksi
                        </button>
                        <a href="{{ route('pengurus.transaksi.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterKategori(jenis) {
        const select  = document.querySelector('select[name="kategori_id"]');
        const options = select.querySelectorAll('option[data-jenis]');

        options.forEach(opt => {
            opt.style.display = opt.dataset.jenis === jenis ? '' : 'none';
        });

        select.value = '';
    }

    // Jalankan saat halaman load jika ada old value
    const oldJenis = '{{ old("jenis") }}';
    if (oldJenis) filterKategori(oldJenis);
</script>
@endpush