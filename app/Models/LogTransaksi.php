<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaksi extends Model
{
    use HasFactory;

    protected $table      = 'log_transaksi';
    public    $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'user_id',
        'aksi',
        'data_lama',
        'data_baru',
        'keterangan',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'data_lama'  => 'array',
        'data_baru'  => 'array',
        'created_at' => 'datetime',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // SCOPE
    // ============================================

    public function scopeTambah($query)
    {
        return $query->where('aksi', 'tambah');
    }

    public function scopeEdit($query)
    {
        return $query->where('aksi', 'edit');
    }

    public function scopeHapus($query)
    {
        return $query->where('aksi', 'hapus');
    }

    // ============================================
    // HELPER
    // ============================================

    public static function catat(
        ?int $transaksiId,
        int $userId,
        string $aksi,
        ?array $dataLama = null,
        ?array $dataBaru = null,
        ?string $keterangan = null
    ): void {
        self::create([
            'transaksi_id' => $transaksiId,
            'user_id'      => $userId,
            'aksi'         => $aksi,
            'data_lama'    => $dataLama,
            'data_baru'    => $dataBaru,
            'keterangan'   => $keterangan,
            'ip_address'   => request()->ip(),
            'created_at'   => now(),
        ]);
    }
}