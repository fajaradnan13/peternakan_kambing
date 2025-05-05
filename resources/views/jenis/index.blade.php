@extends('layouts.app')

@section('title', 'Jenis Kambing')

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
</style>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Jenis Kambing</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus"></i> Tambah Jenis
            </button>
            <a href="{{ route('jenis.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('jenis.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="jenis-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Kambing</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jenis Kambing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis_kambing">Jenis Kambing</label>
                        <input type="text" class="form-control" id="jenis_kambing" name="jenis_kambing" required>
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
                <h5 class="modal-title">Edit Jenis Kambing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_jenis_kambing">Jenis Kambing</label>
                        <input type="text" class="form-control" id="edit_jenis_kambing" name="jenis_kambing" required>
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

    var table = $('#jenis-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('jenis.index') }}",
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
            {data: 'jenis_kambing', name: 'jenis_kambing'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
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

    // Tambah Data
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('jenis.store') }}",
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
    $(document).on('click', '.edit-jenis', function() {
        var id = $(this).data('id');
        $.get("{{ url('jenis') }}/" + id + "/edit", function(data) {
            $('#edit_id').val(data.id);
            $('#edit_jenis_kambing').val(data.jenis_kambing);
            $('#editModal').modal('show');
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: "{{ url('jenis') }}/" + id,
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
    $(document).on('click', '.delete-jenis', function() {
        if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('jenis') }}/" + id,
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