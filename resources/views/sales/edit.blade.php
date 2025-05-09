@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Penjualan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Penjualan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="kode_transaksi">Kode Transaksi</label>
                    <input type="text" class="form-control" id="kode_transaksi" value="{{ $sale->kode_transaksi }}" readonly>
                </div>
                <div class="form-group">
                    <label for="tgl_penjualan">Tanggal Penjualan</label>
                    <input type="date" class="form-control @error('tgl_penjualan') is-invalid @enderror" id="tgl_penjualan" name="tgl_penjualan" value="{{ old('tgl_penjualan', $sale->tgl_penjualan->format('Y-m-d')) }}" required>
                    @error('tgl_penjualan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kambing_id">Kambing</label>
                    <select class="form-control @error('kambing_id') is-invalid @enderror" id="kambing_id" name="kambing_id" required>
                        <option value="">Pilih Kambing</option>
                        @foreach($kambings as $kambing)
                            <option value="{{ $kambing->id }}" {{ old('kambing_id', $sale->kambing_id) == $kambing->id ? 'selected' : '' }}>
                                {{ $kambing->kode_kambing }} - {{ $kambing->nama_kambing }}
                            </option>
                        @endforeach
                    </select>
                    @error('kambing_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $sale->harga_jual) }}" required>
                    @error('harga_jual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="pembeli">Pembeli</label>
                    <input type="text" class="form-control @error('pembeli') is-invalid @enderror" id="pembeli" name="pembeli" value="{{ old('pembeli', $sale->pembeli) }}" required>
                    @error('pembeli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $sale->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format harga jual
    $('#harga_jual').on('input', function() {
        var value = $(this).val();
        if (value < 0) {
            $(this).val(0);
        }
    });
});
</script>
@endpush 