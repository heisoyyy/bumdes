@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Edit Kategori')
@section('page-subtitle', 'Perbarui data kategori')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-pencil me-2 text-warning"></i>
                    Form Edit Kategori
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.kategori.update', $kategori->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama_kategori"
                                   class="form-control @error('nama_kategori') is-invalid @enderror"
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
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
                                <option value="pemasukan"   {{ old('jenis', $kategori->jenis) === 'pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ old('jenis', $kategori->jenis) === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                            @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi"
                                      class="form-control"
                                      rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Update
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