<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Saldo awal
        $saldoAwal = SaldoAwal::sum('nominal');

        // Total pemasukan
        $totalPemasukan = Transaksi::aktif()
            ->pemasukan()
            ->sum('nominal');

        // Total pengeluaran
        $totalPengeluaran = Transaksi::aktif()
            ->pengeluaran()
            ->sum('nominal');

        // Saldo saat ini
        $saldoSekarang = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // Total pemasukan bulan ini
        $totalPemasukanBulanIni = Transaksi::aktif()
            ->pemasukan()
            ->periode(now()->month, now()->year)
            ->sum('nominal');

        // Total pengeluaran bulan ini
        $totalPengeluaranBulanIni = Transaksi::aktif()
            ->pengeluaran()
            ->periode(now()->month, now()->year)
            ->sum('nominal');

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::aktif()
            ->with(['kategori', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data grafik bulanan (12 bulan terakhir)
        $grafikData = $this->getGrafikData();

        // Notifikasi belum dibaca
        $notifikasiCount = Notifikasi::where('user_id', Auth::id())
            ->belumDibaca()
            ->count();

        return view('pengurus.dashboard', compact(
            'saldoSekarang',
            'totalPemasukan',
            'totalPengeluaran',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'transaksiTerbaru',
            'grafikData',
            'notifikasiCount',
        ));
    }

    private function getGrafikData(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan  = now()->subMonths($i)->month;
            $tahun  = now()->subMonths($i)->year;
            $label  = now()->subMonths($i)->translatedFormat('M Y');

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