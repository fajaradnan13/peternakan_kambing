@extends('layouts.app')

@section('title', 'Data Alat dan Perlengkapan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Alat dan Perlengkapan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus"></i> Tambah Alat/Perlengkapan
            </button>
            <a href="{{ route('equipments.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('equipments.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="equipments-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tanggal Beli</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Jumlah Beli</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Alat/Perlengkapan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select class="form-control" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Perlengkapan Kandang">Perlengkapan Kandang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl_beli">Tanggal Beli</label>
                        <input type="date" class="form-control" id="tgl_beli" name="tgl_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_beli">Jumlah Beli</label>
                        <input type="number" class="form-control" id="jumlah_beli" name="jumlah_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Alat/Perlengkapan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori">Kategori</label>
                        <select class="form-control" id="edit_kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Perlengkapan Kandang">Perlengkapan Kandang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_tgl_beli">Tanggal Beli</label>
                        <input type="date" class="form-control" id="edit_tgl_beli" name="tgl_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_satuan">Satuan</label>
                        <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jumlah_beli">Jumlah Beli</label>
                        <input type="number" class="form-control" id="edit_jumlah_beli" name="jumlah_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#equipments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('equipments.index') }}",
        columns: [
            {
                data: null,
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'nama', name: 'nama'},
            {data: 'kategori', name: 'kategori'},
            {data: 'tgl_beli', name: 'tgl_beli'},
            {data: 'satuan', name: 'satuan'},
            {data: 'harga', name: 'harga'},
            {data: 'jumlah_beli', name: 'jumlah_beli'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        scrollCollapse: true
    });

    // Reset form saat modal ditutup
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
    });

    // Tambah Data
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('equipments.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#addModal').modal('hide');
                table.ajax.reload();
                toastr.success(response.success);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                }
            }
        });
    });

    // Edit Data
    $(document).on('click', '.edit-equipment', function() {
        var id = $(this).data('id');
        $.get("{{ url('equipments') }}/" + id + "/edit", function(data) {
            $('#edit_id').val(data.id);
            $('#edit_nama').val(data.nama);
            $('#edit_kategori').val(data.kategori);
            $('#edit_tgl_beli').val(data.tgl_beli);
            $('#edit_satuan').val(data.satuan);
            $('#edit_harga').val(data.harga);
            $('#edit_jumlah_beli').val(data.jumlah_beli);
            $('#edit_keterangan').val(data.keterangan);
            $('#editModal').modal('show');
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: "{{ url('equipments') }}/" + id,
            type: "PUT",
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                table.ajax.reload();
                toastr.success(response.success);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Terjadi kesalahan saat memperbarui data');
                }
            }
        });
    });

    // Hapus Data
    $(document).on('click', '.delete-equipment', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('equipments') }}/" + id,
                    type: "DELETE",
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan saat menghapus data');
                    }
                });
            }
        });
    });
});
</script>
@endpush 