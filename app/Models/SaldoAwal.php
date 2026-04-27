<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    use HasFactory;

    protected $table = 'saldo_awal';

    protected $fillable = [
        'user_id',
        'nominal',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // HELPER
    // ============================================

    public function getNominalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}