<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'periode_bulan',
        'periode_tahun',
        'file_pdf',
        'file_excel',
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
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

    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('periode_bulan', $bulan)
                     ->where('periode_tahun', $tahun);
    }

    // ============================================
    // HELPER / ACCESSOR
    // ============================================

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1  => 'Januari',   2  => 'Februari',
            3  => 'Maret',     4  => 'April',
            5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',      8  => 'Agustus',
            9  => 'September', 10 => 'Oktober',
            11 => 'November',  12 => 'Desember',
        ];
        return $bulan[$this->periode_bulan] ?? '-';
    }

    public function getFilePdfUrlAttribute(): ?string
    {
        if ($this->file_pdf) {
            return asset('storage/' . $this->file_pdf);
        }
        return null;
    }

    public function getFileExcelUrlAttribute(): ?string
    {
        if ($this->file_excel) {
            return asset('storage/' . $this->file_excel);
        }
        return null;
    }
}