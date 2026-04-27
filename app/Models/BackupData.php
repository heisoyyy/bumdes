<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupData extends Model
{
    use HasFactory;

    protected $table = 'backup_data';

    protected $fillable = [
        'user_id',
        'nama_file',
        'ukuran_file',
        'tipe_backup',
        'status',
        'keterangan',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // SCOPE
    // ============================================

    public function scopeBerhasil($query)
    {
        return $query->where('status', 'berhasil');
    }

    public function scopeManual($query)
    {
        return $query->where('tipe_backup', 'manual');
    }

    public function scopeOtomatis($query)
    {
        return $query->where('tipe_backup', 'otomatis');
    }

    // ============================================
    // HELPER
    // ============================================

    public function isBerhasil(): bool
    {
        return $this->status === 'berhasil';
    }
}