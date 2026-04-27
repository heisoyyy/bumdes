<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
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

    public function scopeBelumDibaca($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeSudahDibaca($query)
    {
        return $query->where('is_read', true);
    }

    // ============================================
    // HELPER
    // ============================================

    public function isSudahDibaca(): bool
    {
        return $this->is_read === true;
    }

    public function tandaiDibaca(): void
    {
        $this->update(['is_read' => true]);
    }
}