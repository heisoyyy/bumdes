<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'no_hp',
        'foto_profil',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'password'   => 'hashed',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    public function saldoAwal()
    {
        return $this->hasMany(SaldoAwal::class, 'user_id');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'user_id');
    }

    public function backupData()
    {
        return $this->hasMany(BackupData::class, 'user_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    public function logTransaksi()
    {
        return $this->hasMany(LogTransaksi::class, 'user_id');
    }

    // Pengurus mengelola akun masyarakat
    public function akunYangDikelola()
    {
        return $this->hasMany(PengelolaanAkun::class, 'pengurus_id');
    }

    // Akun masyarakat yang dikelola pengurus
    public function dikelolaOleh()
    {
        return $this->hasMany(PengelolaanAkun::class, 'masyarakat_id');
    }

    // ============================================
    // HELPER / ACCESSOR
    // ============================================

    public function isPengurus(): bool
    {
        return $this->role === 'pengurus';
    }

    public function isKepalaDesa(): bool
    {
        return $this->role === 'kepala_desa';
    }

    public function isMasyarakat(): bool
    {
        return $this->role === 'masyarakat';
    }

    public function isAktif(): bool
    {
        return $this->is_active === 'aktif';
    }

    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil) {
            return asset('storage/' . $this->foto_profil);
        }
        return asset('images/default-avatar.png');
    }
}