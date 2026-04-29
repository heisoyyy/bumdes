<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $bulan }} {{ $tahun }}</title>
    <style>
        * { font-family: Arial, sans-serif; font-size: 12px; }
        body { margin: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; }
        .header h2 { color: #0d6efd; margin: 0 0 5px; font-size: 16px; }
        .header h3 { color: #198754; margin: 0 0 5px; font-size: 14px; }
        .header p  { margin: 3px 0; color: #666; font-size: 11px; }

        .summary { display: flex; gap: 15px; margin-bottom: 20px; }
        .summary-item {
            flex: 1;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            border-left: 4px solid;
        }
        .summary-item.blue   { border-color: #0d6efd; }
        .summary-item.green  { border-color: #198754; }
        .summary-item.orange { border-color: #fd7e14; }
        .summary-item .label { color: #718096; font-size: 10px; text-transform: uppercase; }
        .summary-item .value { color: #2d3748; font-size: 14px; font-weight: bold; margin-top: 3px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th {
            background: #0d6efd;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }

        .pemasukan   { color: #198754; font-weight: bold; }
        .pengeluaran { color: #dc3545; font-weight: bold; }

        .footer { margin-top: 30px; text-align: right; }
        .footer .ttd { margin-top: 60px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>BUMDes Kampar Sejahtera</h2>
        <h3>LAPORAN KEUANGAN</h3>
        <p>Periode: {{ $bulan }} {{ $tahun }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-item blue">
            <div class="label">Saldo Awal</div>
            <div class="value">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item green">
            <div class="label">Total Pemasukan</div>
            <div class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item orange">
            <div class="label">Total Pengeluaran</div>
            <div class="value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item blue">
            <div class="label">Saldo Akhir</div>
            <div class="value">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                <td>{{ $trx->kategori->nama_kategori ?? '-' }}</td>
                <td class="{{ $trx->jenis === 'pemasukan' ? 'pemasukan' : 'pengeluaran' }}">
                    {{ ucfirst($trx->jenis) }}
                </td>
                <td class="{{ $trx->jenis === 'pemasukan' ? 'pemasukan' : 'pengeluaran' }}">
                    Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                </td>
                <td>{{ $trx->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#718096;">
                    Tidak ada transaksi pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer TTD -->
    <div class="footer">
        <p>Kampar, {{ now()->format('d F Y') }}</p>
        <p>Pengurus BUMDes Kampar Sejahtera</p>
        <div class="ttd">
            <p>( _________________________ )</p>
        </div>
    </div>
</body>
</html>