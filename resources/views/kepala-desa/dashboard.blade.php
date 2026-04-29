@extends('layouts.app')

@section('title', 'Dashboard Kepala Desa')

@section('sidebar-menu')
    @include('layouts.sidebar-kepaladesa')
@endsection

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Monitoring keuangan BUMDes Kampar Sejahtera')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-blue">
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
            <div class="stat-value">Rp {{ number_format($saldoSekarang, 0, ',', '.') }}</div>
            <div class="stat-label">Saldo BUMDes</div>
            <div class="stat-sub">Total saldo saat ini</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-green">
            <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
            <div class="stat-sub">Bulan ini: Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-orange">
            <div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
            <div class="stat-sub">Bulan ini: Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-teal">
            <div class="stat-icon"><i class="bi bi-calendar3"></i></div>
            <div class="stat-value">{{ now()->translatedFormat('F Y') }}</div>
            <div class="stat-label">Periode Aktif</div>
            <div class="stat-sub">{{ now()->translatedFormat('d F Y') }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Grafik --}}
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-bar-chart me-2 text-primary"></i>
                    Grafik Keuangan 12 Bulan Terakhir
                </h6>
            </div>
            <div class="card-body">
                <canvas id="grafikKeuangan" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="bi bi-pie-chart me-2 text-success"></i>
                    Ringkasan Bulan Ini
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
                        <strong style="font-size:13px;">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:12px;height:12px;background:#fd7e14;border-radius:3px;"></div>
                            <span style="font-size:13px;">Pengeluaran</span>
                        </div>
                        <strong style="font-size:13px;">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Transaksi Terbaru
                </h6>
                <a href="{{ route('kepaladesa.log-transaksi.index') }}"
                   class="btn btn-sm btn-outline-primary">
                    Lihat Log Lengkap
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Saldo Setelah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $trx)
                            <tr>
                                <td><code style="font-size:12px;">{{ $trx->kode_transaksi }}</code></td>
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
                                <td>Rp {{ number_format($trx->saldo_setelah, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
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
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: pemasukan,
                    backgroundColor: 'rgba(25,135,84,0.8)',
                    borderRadius: 6,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    backgroundColor: 'rgba(253,126,20,0.8)',
                    borderRadius: 6,
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