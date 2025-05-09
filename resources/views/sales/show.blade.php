@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penjualan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Penjualan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 200px">Kode Transaksi</th>
                            <td>{{ $sale->kode_transaksi }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penjualan</th>
                            <td>{{ $sale->tgl_penjualan->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Kode Kambing</th>
                            <td>{{ $sale->kambing->kode_kambing }}</td>
                        </tr>
                        <tr>
                            <th>Nama Kambing</th>
                            <td>{{ $sale->kambing->nama_kambing }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kambing</th>
                            <td>{{ $sale->kambing->jenis->nama_jenis }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 200px">Harga Jual</th>
                            <td>Rp {{ number_format($sale->harga_jual, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Pembeli</th>
                            <td>{{ $sale->pembeli }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $sale->keterangan ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 