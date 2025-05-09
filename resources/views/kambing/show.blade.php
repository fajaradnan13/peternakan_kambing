@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Kambing</h3>
                    <div class="card-tools">
                        <a href="{{ route('kambing.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Kode Kambing</th>
                                    <td>{{ $kambing->kode_kambing }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Kambing</th>
                                    <td>{{ $kambing->nama_kambing }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <td>{{ $kambing->jenis->jenis_kambing }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $kambing->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Beli</th>
                                    <td>{{ $kambing->tanggal_beli->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Umur</th>
                                    <td>{{ $kambing->umur }} bulan</td>
                                </tr>
                                <tr>
                                    <th>Harga Beli</th>
                                    <td>Rp {{ number_format($kambing->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Warna</th>
                                    <td>{{ $kambing->warna }}</td>
                                </tr>
                                <tr>
                                    <th>Kandang</th>
                                    <td>{{ $kambing->barn->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($kambing->status === 'Ternak')
                                            <span class="badge badge-success">Ternak</span>
                                        @else
                                            <span class="badge badge-warning">Terjual</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $kambing->keterangan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h5>Foto Kambing</h5>
                                @if($kambing->foto)
                                    <img src="{{ asset($kambing->foto) }}" alt="Foto Kambing" class="img-fluid" style="max-height: 300px;">
                                @else
                                    <p>Tidak ada foto</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 