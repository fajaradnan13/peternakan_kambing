@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kesehatan Kambing</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <a href="{{ route('health_record.export.csv') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                        <a href="{{ route('health_record.export.pdf') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="health-record-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Kambing</th>
                                    <th>Nama Kambing</th>
                                    <th>Tanggal Pemeriksaan</th>
                                    <th>Kondisi Kesehatan</th>
                                    <th>Kehamilan</th>
                                    <th>Kondisi</th>
                                    <th>Perawatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="goat_id">Kambing</label>
                        <select class="form-control" id="goat_id" name="goat_id" required>
                            <option value="">Pilih Kambing</option>
                            @foreach($kambings as $kambing)
                                <option value="{{ $kambing->id }}">{{ $kambing->kode_kambing }} - {{ $kambing->nama_kambing }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="checkup_date">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="checkup_date" name="checkup_date" required>
                    </div>
                    <div class="form-group">
                        <label for="condition">Kondisi</label>
                        <input type="text" class="form-control" id="condition" name="condition" required>
                    </div>
                    <div class="form-group">
                        <label>Kondisi Kesehatan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_kesehatan" id="kondisi_sehat" value="Sehat" checked>
                            <label class="form-check-label" for="kondisi_sehat">
                                Sehat
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_kesehatan" id="kondisi_sakit" value="Sakit">
                            <label class="form-check-label" for="kondisi_sakit">
                                Sakit
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kehamilan</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="kehamilan" name="kehamilan">
                            <label class="custom-control-label" for="kehamilan">Hamil</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="treatment">Perawatan</label>
                        <textarea class="form-control" id="treatment" name="treatment" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
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
                <h5 class="modal-title">Edit Data Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_goat_id">Kambing</label>
                        <select class="form-control" id="edit_goat_id" name="goat_id" required>
                            <option value="">Pilih Kambing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_checkup_date">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="edit_checkup_date" name="checkup_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_condition">Kondisi</label>
                        <input type="text" class="form-control" id="edit_condition" name="condition" required>
                    </div>
                    <div class="form-group">
                        <label>Kondisi Kesehatan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_kesehatan" id="edit_kondisi_sehat" value="Sehat">
                            <label class="form-check-label" for="edit_kondisi_sehat">
                                Sehat
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kondisi_kesehatan" id="edit_kondisi_sakit" value="Sakit">
                            <label class="form-check-label" for="edit_kondisi_sakit">
                                Sakit
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kehamilan</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_kehamilan" name="kehamilan">
                            <label class="custom-control-label" for="edit_kehamilan">Hamil</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_treatment">Perawatan</label>
                        <textarea class="form-control" id="edit_treatment" name="treatment" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Catatan</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
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
                <h5 class="modal-title">Detail Data Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Kode Kambing</th>
                        <td id="show_kode_kambing"></td>
                    </tr>
                    <tr>
                        <th>Nama Kambing</th>
                        <td id="show_nama_kambing"></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemeriksaan</th>
                        <td id="show_checkup_date"></td>
                    </tr>
                    <tr>
                        <th>Kondisi</th>
                        <td id="show_condition"></td>
                    </tr>
                    <tr>
                        <th>Kondisi Kesehatan</th>
                        <td id="show_kondisi_kesehatan"></td>
                    </tr>
                    <tr>
                        <th>Kehamilan</th>
                        <td id="show_kehamilan"></td>
                    </tr>
                    <tr>
                        <th>Perawatan</th>
                        <td id="show_treatment"></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td id="show_notes"></td>
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

    var table = $('#health-record-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('health_record.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'kode_kambing', name: 'kode_kambing'},
            {data: 'nama_kambing', name: 'nama_kambing'},
            {data: 'checkup_date', name: 'checkup_date'},
            {data: 'kondisi_kesehatan', name: 'kondisi_kesehatan'},
            {data: 'kehamilan', name: 'kehamilan'},
            {data: 'condition', name: 'condition'},
            {data: 'treatment', name: 'treatment'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Reset form saat modal ditutup
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
    });

    // Tambah Data
    $('#addForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        // Konversi nilai kehamilan menjadi boolean
        formData = formData.map(function(item) {
            if (item.name === 'kehamilan') {
                item.value = $('#kehamilan').is(':checked') ? '1' : '0';
            }
            return item;
        });
        
        $.ajax({
            url: "{{ route('health_record.store') }}",
            type: "POST",
            data: $.param(formData),
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
    $(document).on('click', '.show-record', function() {
        var id = $(this).data('id');
        $.get("{{ url('health_record') }}/" + id, function(data) {
            $('#show_kode_kambing').text(data.kambing ? data.kambing.kode_kambing : '-');
            $('#show_nama_kambing').text(data.kambing ? data.kambing.nama_kambing : '-');
            $('#show_checkup_date').text(data.checkup_date);
            $('#show_condition').text(data.condition);
            $('#show_kondisi_kesehatan').text(data.kondisi_kesehatan);
            $('#show_kehamilan').text(data.kehamilan ? 'Ya' : 'Tidak');
            $('#show_treatment').text(data.treatment || '-');
            $('#show_notes').text(data.notes || '-');
        });
    });

    // Edit Data
    $(document).on('click', '.edit-record', function() {
        var id = $(this).data('id');
        $.get("{{ url('health_record') }}/" + id + "/edit", function(data) {
            $('#edit_id').val(data.record.id);
            $('#edit_goat_id').val(data.record.goat_id);
            $('#edit_checkup_date').val(data.record.checkup_date);
            $('#edit_condition').val(data.record.condition);
            $('#edit_treatment').val(data.record.treatment);
            $('#edit_notes').val(data.record.notes);
            
            // Set kondisi kesehatan
            if (data.record.kondisi_kesehatan === 'Sehat') {
                $('#edit_kondisi_sehat').prop('checked', true);
            } else {
                $('#edit_kondisi_sakit').prop('checked', true);
            }
            
            // Set kehamilan
            $('#edit_kehamilan').prop('checked', data.record.kehamilan);
            
            // Update dropdown kambing
            var kambingOptions = '<option value="">Pilih Kambing</option>';
            data.kambings.forEach(function(kambing) {
                var selected = (kambing.id == data.record.goat_id) ? 'selected' : '';
                kambingOptions += '<option value="' + kambing.id + '" ' + selected + '>' + kambing.kode_kambing + ' - ' + kambing.nama_kambing + '</option>';
            });
            $('#edit_goat_id').html(kambingOptions);
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var formData = $(this).serializeArray();
        // Konversi nilai kehamilan menjadi boolean
        formData = formData.map(function(item) {
            if (item.name === 'kehamilan') {
                item.value = $('#edit_kehamilan').is(':checked') ? '1' : '0';
            }
            return item;
        });
        
        $.ajax({
            url: "{{ url('health_record') }}/" + id,
            type: "PUT",
            data: $.param(formData),
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
    $(document).on('click', '.delete-record', function() {
        if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('health_record') }}/" + id,
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