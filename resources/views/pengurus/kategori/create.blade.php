@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Tambah Kategori')
@section('page-subtitle', 'Tambah kategori transaksi baru')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Form Tambah Kategori
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.kategori.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_kategori"
                                   class="form-control @error('nama_kategori') is-invalid @enderror"
                                   placeholder="Contoh: Hasil Usaha"
                                   value="{{ old('nama_kategori') }}"
                                   required>
                            @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis"
                                    class="form-select @error('jenis') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Jenis</option>
                                <option value="pemasukan"   {{ old('jenis') === 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ old('jenis') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                            @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <a href="{{ route('pengurus.kategori.index') }}"
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