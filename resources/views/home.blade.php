@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('sales.export.pdf') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Kambing Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kambing</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKambing }}</div>
                            <div class="text-xs text-gray-600 mt-2">
                                Jantan: {{ $kambingJantan }} | Betina: {{ $kambingBetina }}<br>
                                Aktif: {{ $kambingAktif }} | Terjual: {{ $kambingTerjual }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sheep fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 mt-2">
                                Bulan Ini: Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}<br>
                                Jumlah Transaksi: {{ $jumlahPenjualan }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Pakan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stok Pakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPakan }} kg</div>
                            <div class="text-xs text-gray-600 mt-2">
                                Hampir Habis: {{ $pakanHampirHabis }} item
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wheat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kandang Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('barn.index') }}" class="text-decoration-none">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kandang</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKandang }} Kandang</div>
                                <div class="text-xs text-gray-600 mt-2">
                                    @forelse($barnsInfo->take(2) as $barn)
                                        @if($barn->catatan && strpos($barn->catatan, '/') !== false)
                                            @php 
                                                list($kap, $tersedia) = explode('/', $barn->catatan);
                                                $isFull = $tersedia == 0;
                                            @endphp
                                            <div class="mb-1">
                                                Kandang {{ $barn->name }}: 
                                                <span class="badge badge-{{ $isFull ? 'danger' : 'success' }}">
                                                    {{ $kap }}/{{ $tersedia }}
                                                </span>
                                            </div>
                                        @endif
                                    @empty
                                        <div>Belum ada data kandang</div>
                                    @endforelse
                                    <div class="mt-1"><i class="fas fa-arrow-right"></i> Lihat Semua</div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-home fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Ringkasan Laba/Rugi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Laba/Rugi</h6>
                    <form method="get" action="" class="form-inline">
                        <label for="periode" class="mr-2 mb-0">Periode:</label>
                        <select name="periode" id="periode" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>1 Minggu</option>
                            <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>1 Bulan</option>
                            <option value="tahun" {{ $periode == 'tahun' ? 'selected' : '' }}>1 Tahun</option>
                            <option value="all" {{ $periode == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="p-3 rounded border bg-light mb-2">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt fa-lg text-primary mr-2"></i>
                                    <span class="font-weight-bold">Periode:</span>
                                    <span class="ml-2">{{ $labelPeriode }}</span>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 col-md-6 mb-1">
                                        <i class="fas fa-money-bill-wave text-success mr-1"></i>
                                        Penjualan:
                                        <span class="font-weight-bold text-success">Rp {{ number_format($penjualan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6 col-md-6 mb-1">
                                        <i class="fas fa-shopping-cart text-danger mr-1"></i>
                                        Pembelian Kambing:
                                        <span class="font-weight-bold text-danger">Rp {{ number_format($pembelianKambing, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6 col-md-6 mb-1">
                                        <i class="fas fa-leaf text-warning mr-1"></i>
                                        Pembelian Pakan:
                                        <span class="font-weight-bold text-warning">Rp {{ number_format($pembelianPakan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6 col-md-6 mb-1">
                                        <i class="fas fa-tools text-info mr-1"></i>
                                        Pembelian Alat:
                                        <span class="font-weight-bold text-info">Rp {{ number_format($pembelianAlat, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded text-center shadow-sm {{ $labaRugi < 0 ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                <div class="mb-2">
                                    <i class="fas fa-coins fa-2x"></i>
                                </div>
                                <div class="h3 font-weight-bold mb-0">Rp {{ number_format($labaRugi, 0, ',', '.') }}</div>
                                <div style="font-size:1rem;font-weight:normal;">Laba/Rugi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Kambing per Jenis</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="jenisKambingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Kambing Terbaru -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kambing Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kambingTerbaru as $kambing)
                                <tr>
                                    <td>{{ $kambing->kode_kambing }}</td>
                                    <td>{{ $kambing->nama_kambing }}</td>
                                    <td>
                                        <span class="badge badge-{{ $kambing->status == 'Aktif' ? 'success' : 'warning' }}">
                                            {{ $kambing->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjualan Terbaru -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Penjualan Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kambing</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualanTerbaru as $penjualan)
                                <tr>
                                    <td>{{ $penjualan->tgl_penjualan->format('d/m/Y') }}</td>
                                    <td>{{ $penjualan->kambing->nama_kambing }}</td>
                                    <td>Rp {{ number_format($penjualan->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekam Medis Terbaru -->
        <div class="col-xl-4 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rekam Medis Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kambing</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekamMedisTerbaru as $rm)
                                <tr>
                                    <td>{{ $rm->checkup_date ? $rm->checkup_date->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $rm->kambing->nama_kambing }}</td>
                                    <td>
                                        <span class="badge badge-{{ $rm->kondisi_kesehatan == 'Sehat' ? 'success' : 'danger' }}">
                                            {{ $rm->kondisi_kesehatan }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Jenis Kambing
var ctxPie = document.getElementById('jenisKambingChart').getContext('2d');
var jenisData = @json($kambingPerJenis);
var labelsPie = jenisData.map(item => item.nama_jenis);
var dataPie = jenisData.map(item => item.kambings_count);

new Chart(ctxPie, {
    type: 'doughnut',
    data: {
        labels: labelsPie,
        datasets: [{
            data: dataPie,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
