<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengelolaanAkun extends Model
{
    use HasFactory;

    protected $table      = 'pengelolaan_akun';
    public    $timestamps = false;

    protected $fillable = [
        'pengurus_id',
        'masyarakat_id',
        'aksi',
        'keterangan',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    public function masyarakat()
    {
        return $this->belongsTo(User::class, 'masyarakat_id');
    }

    // ============================================
    // HELPER
    // ============================================

    public static function catat(
        int $pengurusId,
        int $masyarakatId,
        string $aksi,
        ?string $keterangan = null
    ): void {
        self::create([
            'pengurus_id'   => $pengurusId,
            'masyarakat_id' => $masyarakatId,
            'aksi'          => $aksi,
            'keterangan'    => $keterangan,
            'created_at'    => now(),
        ]);
    }
}