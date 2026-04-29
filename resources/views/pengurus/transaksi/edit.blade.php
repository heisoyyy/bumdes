@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Edit Transaksi')
@section('page-subtitle', 'Perbarui data transaksi')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-pencil me-2 text-warning"></i>
                    Form Edit Transaksi
                    <code class="ms-2" style="font-size:12px;">{{ $transaksi->kode_transaksi }}</code>
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.transaksi.update', $transaksi->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Jenis Transaksi --}}
                        <div class="col-12">
                            <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="jenis" id="pemasukan" value="pemasukan"
                                           {{ old('jenis', $transaksi->jenis) === 'pemasukan' ? 'checked' : '' }}
                                           onchange="filterKategori('pemasukan')">
                                    <label class="form-check-label text-success" for="pemasukan">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="jenis" id="pengeluaran" value="pengeluaran"
                                           {{ old('jenis', $transaksi->jenis) === 'pengeluaran' ? 'checked' : '' }}
                                           onchange="filterKategori('pengeluaran')">
                                    <label class="form-check-label text-danger" for="pengeluaran">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                    </label>
                                </div>
                            </div>
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
                                        {{ old('kategori_id', $transaksi->kategori_id) == $kat->id ? 'selected' : '' }}>
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
                                   value="{{ old('nominal', $transaksi->nominal) }}"
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
                                   value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}"
                                   required>
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bukti Transaksi --}}
                        <div class="col-md-6">
                            <label class="form-label">Bukti Transaksi</label>
                            @if($transaksi->bukti_transaksi)
                            <div class="mb-2">
                                <img src="{{ $transaksi->bukti_transaksi_url }}"
                                     alt="Bukti"
                                     style="height:60px;border-radius:8px;border:1px solid #e2e8f0;">
                                <div style="font-size:11px;color:#718096;margin-top:4px;">
                                    Bukti saat ini. Upload baru untuk mengganti.
                                </div>
                            </div>
                            @endif
                            <input type="file"
                                   name="bukti_transaksi"
                                   class="form-control @error('bukti_transaksi') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg">
                            @error('bukti_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="3">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Update Transaksi
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

    const oldJenis = '{{ old("jenis", $transaksi->jenis) }}';
    if (oldJenis) filterKategori(oldJenis);
</script>
@endpush