<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'kategori_id',
        'jenis',
        'nominal',
        'saldo_setelah',
        'tanggal',
        'keterangan',
        'bukti_transaksi',
        'deleted_at',
    ];

    protected $casts = [
        'nominal'       => 'decimal:2',
        'saldo_setelah' => 'decimal:2',
        'tanggal'       => 'date',
        'deleted_at'    => 'datetime',
    ];

    // ============================================
    // RELASI
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'kategori_id');
    }

    public function logTransaksi()
    {
        return $this->hasMany(LogTransaksi::class, 'transaksi_id');
    }

    // ============================================
    // SCOPE
    // ============================================

    public function scopeAktif($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }

    // ============================================
    // HELPER / ACCESSOR
    // ============================================

    public function getNominalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getSaldoSetelahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->saldo_setelah, 0, ',', '.');
    }

    public function getBuktiTransaksiUrlAttribute(): ?string
    {
        if ($this->bukti_transaksi) {
            return asset('storage/' . $this->bukti_transaksi);
        }
        return null;
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    // Generate kode transaksi otomatis
    public static function generateKode(): string
    {
        $tahun  = date('Y');
        $bulan  = date('m');
        $prefix = 'TRX-' . $tahun . $bulan . '-';

        $last = self::whereRaw("kode_transaksi LIKE '{$prefix}%'")
                    ->orderBy('kode_transaksi', 'desc')
                    ->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_transaksi, -4);
            $newNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }
}