@extends('layouts.app')

@section('title', 'Edit Saldo Awal')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Edit Saldo Awal')
@section('page-subtitle', 'Perbarui data saldo awal')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-pencil me-2 text-warning"></i>
                    Form Edit Saldo Awal
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.saldo-awal.update', $saldoAwal->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="nominal"
                                   class="form-control @error('nominal') is-invalid @enderror"
                                   value="{{ old('nominal', $saldoAwal->nominal) }}"
                                   min="1"
                                   required>
                            @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $saldoAwal->tanggal->format('Y-m-d')) }}"
                                   required>
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="3">{{ old('keterangan', $saldoAwal->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Update
                        </button>
                        <a href="{{ route('pengurus.saldo-awal.index') }}"
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