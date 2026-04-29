<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\Controller;
use App\Models\SaldoAwal;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Saldo
        $saldoAwal        = SaldoAwal::sum('nominal');
        $totalPemasukan   = Transaksi::aktif()->pemasukan()->sum('nominal');
        $totalPengeluaran = Transaksi::aktif()->pengeluaran()->sum('nominal');
        $saldoSekarang    = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // Pemasukan & pengeluaran bulan ini
        $totalPemasukanBulanIni = Transaksi::aktif()
            ->pemasukan()
            ->periode(now()->month, now()->year)
            ->sum('nominal');

        $totalPengeluaranBulanIni = Transaksi::aktif()
            ->pengeluaran()
            ->periode(now()->month, now()->year)
            ->sum('nominal');

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::aktif()
            ->with('kategori')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // Grafik bulanan
        $grafikData = $this->getGrafikData();

        return view('kepala-desa.dashboard', compact(
            'saldoSekarang',
            'totalPemasukan',
            'totalPengeluaran',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'transaksiTerbaru',
            'grafikData',
        ));
    }

    private function getGrafikData(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->month;
            $tahun = now()->subMonths($i)->year;
            $label = now()->subMonths($i)->translatedFormat('M Y');

            $pemasukan = Transaksi::aktif()
                ->pemasukan()
                ->periode($bulan, $tahun)
                ->sum('nominal');

            $pengeluaran = Transaksi::aktif()
                ->pengeluaran()
                ->periode($bulan, $tahun)
                ->sum('nominal');

            $data[] = [
                'label'       => $label,
                'pemasukan'   => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
            ];
        }
        return $data;
    }
}