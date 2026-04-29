@extends('layouts.app')

@section('title', 'Edit Akun Masyarakat')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Edit Akun Masyarakat')
@section('page-subtitle', 'Perbarui data akun masyarakat')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-person-gear me-2 text-warning"></i>
                    Form Edit Akun: {{ $masyarakat->name }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pengurus.akun-masyarakat.update', $masyarakat->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $masyarakat->name) }}"
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $masyarakat->username) }}"
                                   required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $masyarakat->email) }}"
                                   required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text"
                                   name="no_hp"
                                   class="form-control"
                                   value="{{ old('no_hp', $masyarakat->no_hp) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Foto Profil</label>
                            @if($masyarakat->foto_profil)
                            <div class="mb-2">
                                <img src="{{ $masyarakat->foto_profil_url }}"
                                     alt="Foto"
                                     style="height:60px;border-radius:8px;">
                            </div>
                            @endif
                            <input type="file"
                                   name="foto_profil"
                                   class="form-control"
                                   accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Update Akun
                        </button>
                        <a href="{{ route('pengurus.akun-masyarakat.index') }}"
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