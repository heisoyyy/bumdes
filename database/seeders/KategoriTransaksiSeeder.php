<?php

namespace Database\Seeders;

use App\Models\KategoriTransaksi;
use Illuminate\Database\Seeder;

class KategoriTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Pemasukan
        $pemasukan = [
            [
                'nama_kategori' => 'Modal Awal',
                'jenis'         => 'pemasukan',
                'deskripsi'     => 'Modal awal BUMDes dari pemerintah desa',
            ],
            [
                'nama_kategori' => 'Hasil Usaha',
                'jenis'         => 'pemasukan',
                'deskripsi'     => 'Pendapatan dari unit usaha BUMDes',
            ],
            [
                'nama_kategori' => 'Bantuan Pemerintah',
                'jenis'         => 'pemasukan',
                'deskripsi'     => 'Dana bantuan dari pemerintah pusat/daerah',
            ],
            [
                'nama_kategori' => 'Simpanan Masyarakat',
                'jenis'         => 'pemasukan',
                'deskripsi'     => 'Dana simpanan dari masyarakat desa',
            ],
            [
                'nama_kategori' => 'Pendapatan Lain-lain',
                'jenis'         => 'pemasukan',
                'deskripsi'     => 'Pendapatan di luar kategori utama',
            ],
        ];

        // Pengeluaran
        $pengeluaran = [
            [
                'nama_kategori' => 'Operasional',
                'jenis'         => 'pengeluaran',
                'deskripsi'     => 'Biaya operasional harian BUMDes',
            ],
            [
                'nama_kategori' => 'Gaji Pengurus',
                'jenis'         => 'pengeluaran',
                'deskripsi'     => 'Pembayaran gaji pengurus BUMDes',
            ],
            [
                'nama_kategori' => 'Pembelian Aset',
                'jenis'         => 'pengeluaran',
                'deskripsi'     => 'Pembelian aset untuk kebutuhan BUMDes',
            ],
            [
                'nama_kategori' => 'Biaya Administrasi',
                'jenis'         => 'pengeluaran',
                'deskripsi'     => 'Biaya administrasi dan surat menyurat',
            ],
            [
                'nama_kategori' => 'Pengeluaran Lain-lain',
                'jenis'         => 'pengeluaran',
                'deskripsi'     => 'Pengeluaran di luar kategori utama',
            ],
        ];

        foreach (array_merge($pemasukan, $pengeluaran) as $item) {
            KategoriTransaksi::create($item);
        }
    }
}