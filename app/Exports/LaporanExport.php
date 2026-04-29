<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $transaksis;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $saldoAwal;
    protected $saldoAkhir;
    protected $bulan;
    protected $tahun;

    public function __construct(
        $transaksis,
        $totalPemasukan,
        $totalPengeluaran,
        $saldoAwal,
        $saldoAkhir,
        $bulan,
        $tahun
    ) {
        $this->transaksis       = $transaksis;
        $this->totalPemasukan   = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->saldoAwal        = $saldoAwal;
        $this->saldoAkhir       = $saldoAkhir;
        $this->bulan            = $bulan;
        $this->tahun            = $tahun;
    }

    public function collection()
    {
        return $this->transaksis->map(function ($item, $index) {
            return [
                'no'         => $index + 1,
                'kode'       => $item->kode_transaksi,
                'tanggal'    => $item->tanggal->format('d/m/Y'),
                'kategori'   => $item->kategori->nama_kategori ?? '-',
                'jenis'      => ucfirst($item->jenis),
                'nominal'    => $item->nominal,
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Kategori',
            'Jenis',
            'Nominal (Rp)',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'Laporan ' . $this->bulan . ' ' . $this->tahun;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType'   => 'solid',
                    'startColor' => ['rgb' => '4CAF50'],
                ],
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ];
    }
}