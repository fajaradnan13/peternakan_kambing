@extends('layouts.app')

@section('title', 'Penjualan Kambing')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Penjualan</h1>
        <div>
            <a href="{{ route('sales.export.csv') }}" class="btn btn-success">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('sales.export.pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penjualan
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penjualan</h6>
        </div>
        <div class="card-body">
            <form method="get" class="mb-3">
                <div class="input-group" style="max-width:400px;">
                    <input type="text" name="q" class="form-control" placeholder="Cari kode transaksi, pembeli, kambing..." value="{{ request('q') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="sales-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kambing</th>
                            <th>Harga Jual</th>
                            <th>Pembeli</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $i => $sale)
                        <tr>
                            <td>{{ $sales->firstItem() + $i }}</td>
                            <td>{{ $sale->kode_transaksi }}</td>
                            <td>{{ $sale->tgl_penjualan ? $sale->tgl_penjualan->format('d/m/Y') : '-' }}</td>
                            <td>{{ $sale->kambing ? $sale->kambing->nama_kambing : '-' }}</td>
                            <td>Rp {{ number_format($sale->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $sale->pembeli }}</td>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-label">Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>Kode Transaksi</th>
                        <td id="detail-kode"></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td id="detail-tanggal"></td>
                    </tr>
                    <tr>
                        <th>Kambing</th>
                        <td id="detail-kambing"></td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td id="detail-harga"></td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td id="detail-pembeli"></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td id="detail-keterangan"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#sales-table').DataTable({
        paging: false,
        searching: false,
        info: false
    });

    // Show detail
    $('#sales-table').on('click', '.btn-show', function() {
        var id = $(this).data('id');
        $.get("{{ url('sales') }}/" + id, function(data) {
            $('#detail-kode').text(data.kode_transaksi);
            $('#detail-tanggal').text(data.tgl_penjualan);
            $('#detail-kambing').text(data.kambing.nama_kambing);
            $('#detail-harga').text('Rp ' + data.harga_jual);
            $('#detail-pembeli').text(data.pembeli);
            $('#detail-keterangan').text(data.keterangan || '-');
            $('#modal-detail').modal('show');
        });
    });

    // Delete
    $('#sales-table').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: "{{ url('sales') }}/" + id,
                type: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#sales-table').DataTable().ajax.reload();
                    alert(data.success);
                }
            });
        }
    });
});
</script>
@endpush 