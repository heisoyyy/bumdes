@extends('layouts.app')

@section('title', 'Dashboard Masyarakat')

@section('sidebar-menu')
    @include('layouts.sidebar-masyarakat')
@endsection

@section('page-title', 'Dashboard Transparansi')
@section('page-subtitle', 'Informasi keuangan BUMDes Kampar Sejahtera')

@section('content')

{{-- Info Banner --}}
<div class="alert d-flex align-items-center gap-3 mb-4"
     style="background:linear-gradient(135deg,#e0f2fe,#dcfce7);border:1px solid #bae6fd;border-radius:12px;">
    <i class="bi bi-info-circle-fill text-primary fs-4"></i>
    <div>
        <strong style="font-size:14px;">Transparansi Keuangan BUMDes</strong>
        <div style="font-size:13px;color:#4a5568;margin-top:2px;">
            Halaman ini menampilkan informasi keuangan BUMDes Kampar Sejahtera
            secara transparan untuk masyarakat desa.
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-gradient-blue">
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">Rp {{ number_format($saldoSekarang, 0, ',', '.') }}</div>
            <div class="stat-label">Saldo BUMDes</div>
            <div class="stat-sub">Total saldo saat ini</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-gradient-green">
            <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-value">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</div>
            <div class="stat-label">Pemasukan Bulan Ini</div>
            <div class="stat-sub">Total: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-gradient-orange">
            <div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-value">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</div>
            <div class="stat-label">Pengeluaran Bulan Ini</div>
            <div class="stat-sub">Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Grafik --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-bar-chart me-2 text-primary"></i>
                    Grafik Keuangan 12 Bulan Terakhir
                </h6>
            </div>
            <div class="card-body">
                <canvas id="grafikKeuangan" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-pie-chart me-2 text-success"></i>
                    Perbandingan Bulan Ini
                </h6>
            </div>
            <div class="card-body">
                <canvas id="grafikDonut" height="180"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:12px;height:12px;background:#198754;border-radius:3px;"></div>
                            <span style="font-size:13px;">Pemasukan</span>
                        </div>
                        <strong style="font-size:13px;">
                            Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:12px;height:12px;background:#fd7e14;border-radius:3px;"></div>
                            <span style="font-size:13px;">Pengeluaran</span>
                        </div>
                        <strong style="font-size:13px;">
                            Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-list-ul me-2 text-primary"></i>
                    Daftar Transaksi Terbaru
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $trx)
                            <tr>
                                <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $trx->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    @if($trx->jenis === 'pemasukan')
                                        <span class="badge-pemasukan">Pemasukan</span>
                                    @else
                                        <span class="badge-pengeluaran">Pengeluaran</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $trx->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        {{ $trx->jenis === 'pemasukan' ? '+' : '-' }}
                                        Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const grafikData  = @json($grafikData);
    const labels      = grafikData.map(d => d.label);
    const pemasukan   = grafikData.map(d => d.pemasukan);
    const pengeluaran = grafikData.map(d => d.pengeluaran);

    new Chart(document.getElementById('grafikKeuangan'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukan,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253,126,20,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' +
                            new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('grafikDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Pemasukan','Pengeluaran'],
            datasets: [{
                data: [{{ $totalPemasukanBulanIni }}, {{ $totalPengeluaranBulanIni }}],
                backgroundColor: ['#198754','#fd7e14'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush