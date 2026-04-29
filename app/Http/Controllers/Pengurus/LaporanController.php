<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\SaldoAwal;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('user')
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(15);

        return view('pengurus.laporan.index', compact('laporans'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer|min:2000|max:' . date('Y'),
        ], [
            'periode_bulan.required' => 'Bulan wajib dipilih',
            'periode_tahun.required' => 'Tahun wajib diisi',
        ]);

        $bulan = $request->periode_bulan;
        $tahun = $request->periode_tahun;

        // Ambil data transaksi periode ini
        $transaksis = Transaksi::aktif()
            ->with('kategori')
            ->periode($bulan, $tahun)
            ->orderBy('tanggal')
            ->get();

        $totalPemasukan   = $transaksis->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAwal        = SaldoAwal::sum('nominal');
        $saldoAkhir       = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        $namaBulan = [
            1 => 'Januari',   2 => 'Februari',
            3 => 'Maret',     4 => 'April',
            5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',      8 => 'Agustus',
            9 => 'September', 10 => 'Oktober',
            11 => 'November', 12 => 'Desember',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pengurus.laporan.template-pdf', [
            'transaksis'       => $transaksis,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAwal'        => $saldoAwal,
            'saldoAkhir'       => $saldoAkhir,
            'bulan'            => $namaBulan[$bulan],
            'tahun'            => $tahun,
        ])->setPaper('a4', 'portrait');

        $filenamePdf   = 'laporan-' . $bulan . '-' . $tahun . '-' . time() . '.pdf';
        $pathPdf       = 'laporan/' . $filenamePdf;
        $pdf->save(storage_path('app/public/' . $pathPdf));

        // Generate Excel
        $filenameExcel = 'laporan-' . $bulan . '-' . $tahun . '-' . time() . '.xlsx';
        $pathExcel     = 'laporan/' . $filenameExcel;
        Excel::store(
            new LaporanExport($transaksis, $totalPemasukan, $totalPengeluaran, $saldoAwal, $saldoAkhir, $namaBulan[$bulan], $tahun),
            $pathExcel,
            'public'
        );

        // Simpan record laporan
        Laporan::create([
            'user_id'       => Auth::id(),
            'periode_bulan' => $bulan,
            'periode_tahun' => $tahun,
            'file_pdf'      => $pathPdf,
            'file_excel'    => $pathExcel,
        ]);

        return redirect()->route('pengurus.laporan.index')
            ->with('success', 'Laporan ' . $namaBulan[$bulan] . ' ' . $tahun . ' berhasil digenerate');
    }

    public function downloadPdf($id)
    {
        $laporan = Laporan::findOrFail($id);

        if (!$laporan->file_pdf) {
            return back()->with('error', 'File PDF tidak tersedia');
        }

        return response()->download(
            storage_path('app/public/' . $laporan->file_pdf)
        );
    }

    public function downloadExcel($id)
    {
        $laporan = Laporan::findOrFail($id);

        if (!$laporan->file_excel) {
            return back()->with('error', 'File Excel tidak tersedia');
        }

        return response()->download(
            storage_path('app/public/' . $laporan->file_excel)
        );
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('pengurus.laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
}