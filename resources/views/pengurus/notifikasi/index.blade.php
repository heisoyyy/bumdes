@extends('layouts.app')

@section('title', 'Notifikasi')

@section('sidebar-menu')
    @include('layouts.sidebar-pengurus')
@endsection

@section('page-title', 'Notifikasi')
@section('page-subtitle', 'Daftar notifikasi sistem')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title">
            <i class="bi bi-bell me-2 text-primary"></i>
            Notifikasi
            @if($belumDibaca > 0)
            <span class="badge bg-danger ms-2" style="font-size:11px;">
                {{ $belumDibaca }} belum dibaca
            </span>
            @endif
        </h6>
        @if($belumDibaca > 0)
        <form action="{{ route('pengurus.notifikasi.read.all') }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>
    <div class="card-body p-0">
        @forelse($notifikasis as $notif)
        <div style="padding:15px 20px;border-bottom:1px solid #f0f4f8;
                    background:{{ !$notif->is_read ? '#f0f7ff' : 'white' }};">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div style="width:40px;height:40px;background:{{ !$notif->is_read ? 'linear-gradient(135deg,#0d6efd,#198754)' : '#f0f4f8' }};
                                border-radius:10px;display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <i class="bi bi-bell" style="color:{{ !$notif->is_read ? 'white' : '#718096' }};"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:{{ !$notif->is_read ? '600' : '400' }};color:#2d3748;">
                            {{ $notif->judul }}
                        </div>
                        <div style="font-size:13px;color:#718096;margin-top:3px;">
                            {{ $notif->pesan }}
                        </div>
                        <div style="font-size:11px;color:#a0aec0;margin-top:5px;">
                            <i class="bi bi-clock me-1"></i>
                            {{ $notif->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-1 flex-shrink-0">
                    @if(!$notif->is_read)
                    <form action="{{ route('pengurus.notifikasi.read', $notif->id) }}"
                          method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="btn btn-sm btn-outline-primary"
                                title="Tandai dibaca">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('pengurus.notifikasi.destroy', $notif->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger"
                                title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
            Tidak ada notifikasi
        </div>
        @endforelse
    </div>
    @if($notifikasis->hasPages())
    <div class="card-footer">
        {{ $notifikasis->links() }}
    </div>
    @endif
</div>

@endsection