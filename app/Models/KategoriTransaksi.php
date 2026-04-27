<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTransaksi extends Model
{
    use HasFactory;

    protected $table = 'kategori_transaksi';

    protected $fillable = [
        'nama_kategori',
        'jenis',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'string',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kategori_id');
    }

    // ============================================
    // SCOPE
    // ============================================

    public function scopeAktif($query)
    {
        return $query->where('is_active', 'aktif');
    }

    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    // ============================================
    // HELPER
    // ============================================

    public function isAktif(): bool
    {
        return $this->is_active === 'aktif';
    }
}