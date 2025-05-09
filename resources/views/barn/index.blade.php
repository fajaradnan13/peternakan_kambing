@extends('layouts.app')

@section('title', 'Data Kandang')

@section('content')
<style>
    .dataTables_wrapper .dataTables_scroll {
        position: relative;
        clear: both;
        width: 100%;
    }
    .dataTables_scrollBody {
        position: relative !important;
    }
    .dataTables_scrollHead {
        position: relative !important;
    }
    .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
    .dataTables_scrollBody table {
        width: 100% !important;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #barn-table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    #barn-table thead th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
    }
    #barn-table tbody td {
        border: 1px solid #dee2e6;
        padding: 8px;
        vertical-align: middle;
    }
    #barn-table tbody tr:hover {
        background-color: #f5f5f5;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 10px;
    }
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 10px;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 10px;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05);
    }
</style>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Kandang</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus"></i> Tambah Kandang
            </button>
            <a href="{{ route('barn.export.csv') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('barn.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="barn-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kandang</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                        <th>Catatan</th>
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
                <h5 class="modal-title">Tambah Kandang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Kandang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Lokasi</label>
                        <textarea class="form-control" id="location" name="location" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas</label>
                        <input type="number" class="form-control" id="kapasitas" name="kapasitas" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="penuh">Penuh</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kondisi">Kondisi</label>
                        <select class="form-control" id="kondisi" name="kondisi" required>
                            <option value="baik">Baik</option>
                            <option value="perlu perbaikan">Perlu Perbaikan</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
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
                <h5 class="modal-title">Edit Kandang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Nama Kandang</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_location">Lokasi</label>
                        <textarea class="form-control" id="edit_location" name="location" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_kapasitas">Kapasitas</label>
                        <input type="number" class="form-control" id="edit_kapasitas" name="kapasitas" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="penuh">Penuh</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kondisi">Kondisi</label>
                        <select class="form-control" id="edit_kondisi" name="kondisi" required>
                            <option value="baik">Baik</option>
                            <option value="perlu perbaikan">Perlu Perbaikan</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_catatan">Catatan</label>
                        <textarea class="form-control" id="edit_catatan" name="catatan" rows="2"></textarea>
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

<!-- Modal Show -->
<div class="modal fade" id="showModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kandang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Kandang</th>
                        <td id="show_name"></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td id="show_location"></td>
                    </tr>
                    <tr>
                        <th>Kapasitas</th>
                        <td id="show_kapasitas"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="show_status"></td>
                    </tr>
                    <tr>
                        <th>Kondisi</th>
                        <td id="show_kondisi"></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td id="show_catatan"></td>
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

@push('js')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#barn-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('barn.index') }}",
        columns: [
            {
                data: null,
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%'
            },
            {data: 'name', name: 'name', width: '15%'},
            {data: 'location', name: 'location', width: '15%'},
            {data: 'kapasitas', name: 'kapasitas', width: '10%'},
            {data: 'status', name: 'status', width: '10%'},
            {data: 'kondisi', name: 'kondisi', width: '15%'},
            {
                data: 'catatan',
                name: 'catatan',
                width: '20%',
                render: function(data, type, row) {
                    if (data && data.includes('/')) {
                        var parts = data.split('/');
                        return `<span class='badge badge-primary'>Kapasitas: ${parts[0]}</span> <span class='badge badge-success'>Tersedia: ${parts[1]}</span>`;
                    }
                    return data ? `<span class='badge badge-secondary'>${data}</span>` : '-';
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false, width: '10%'}
        ],
        order: [[1, 'asc']],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        scrollCollapse: true,
        fixedHeader: true,
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control');
            $('.dataTables_length select').addClass('form-control');
        }
    });

    // Reset form saat modal ditutup
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
    });
    $('#editModal').on('hidden.bs.modal', function () {
        $('#editForm')[0].reset();
    });

    // Tambah Data
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('barn.store') }}",
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

    // Show Data
    $(document).on('click', '.show-barn', function() {
        var id = $(this).data('id');
        $.get("{{ url('barn') }}/" + id, function(data) {
            $('#show_name').text(data.name);
            $('#show_location').text(data.location);
            $('#show_kapasitas').text(data.kapasitas);
            $('#show_status').text(data.status);
            $('#show_kondisi').text(data.kondisi);
            $('#show_catatan').text(data.catatan);
        });
    });

    // Edit Data
    $(document).on('click', '.edit-barn', function() {
        var id = $(this).data('id');
        $.get("{{ url('barn') }}/" + id + "/edit", function(data) {
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_location').val(data.location);
            $('#edit_kapasitas').val(data.kapasitas);
            $('#edit_status').val(data.status);
            $('#edit_kondisi').val(data.kondisi);
            $('#edit_catatan').val(data.catatan);
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: "{{ url('barn') }}/" + id,
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
    $(document).on('click', '.delete-barn', function() {
        if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('barn') }}/" + id,
                type: "DELETE",
                success: function(response) {
                    table.ajax.reload();
                    toastr.success(response.success);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat menghapus data');
                }
            });
        }
    });
});
</script>
@endpush 