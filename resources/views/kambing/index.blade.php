@extends('layouts.app')

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
    /* Tambahan CSS untuk memperbaiki tampilan tabel */
    #kambing-table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    #kambing-table thead th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
    }
    #kambing-table tbody td {
        border: 1px solid #dee2e6;
        padding: 8px;
        vertical-align: middle;
    }
    #kambing-table tbody tr:hover {
        background-color: #f5f5f5;
    }
    #kambing-table img {
        max-width: 100px;
        height: auto;
        display: block;
        margin: 0 auto;
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
    /* Style untuk modal */
    .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
    }
    .modal-lg {
        max-width: 900px;
    }
    .modal-content {
        border-radius: 0.3rem;
    }
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-control {
        border-radius: 0.25rem;
    }
    .btn {
        border-radius: 0.25rem;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kambing</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addKambingModal">
                            <i class="fas fa-plus"></i> Tambah Kambing
                        </button>
                        <a href="{{ route('kambing.export.csv') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="kambing-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Kambing</th>
                                    <th>Nama Kambing</th>
                                    <th>Jenis Kambing</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Beli</th>
                                    <th>Umur (bulan)</th>
                                    <th>Harga Beli</th>
                                    <th>Warna</th>
                                    <th>Kandang</th>
                                    <th>Foto</th>
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
<div class="modal fade" id="addKambingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kambing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_kambing">Kode Kambing</label>
                        <input type="text" class="form-control" id="kode_kambing" name="kode_kambing" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_kambing">Nama Kambing</label>
                        <input type="text" class="form-control" id="nama_kambing" name="nama_kambing" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_id">Jenis Kambing</label>
                        <select class="form-control" id="jenis_id" name="jenis_id" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($jenis as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_beli">Tanggal Beli</label>
                        <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="umur">Umur (bulan)</label>
                        <input type="number" class="form-control" id="umur" name="umur" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="harga_beli">Harga Beli</label>
                        <input type="text" class="form-control" id="harga_beli" name="harga_beli" required onkeyup="formatRupiah(this)">
                    </div>
                    <div class="form-group">
                        <label for="warna">Warna</label>
                        <input type="text" class="form-control" id="warna" name="warna" required>
                    </div>
                    <div class="form-group">
                        <label for="barn_id">Kandang</label>
                        <select class="form-control" id="barn_id" name="barn_id" required>
                            <option value="">Pilih Kandang</option>
                            @foreach($barns as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
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
                <h5 class="modal-title">Edit Kambing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_kode_kambing">Kode Kambing</label>
                        <input type="text" class="form-control" id="edit_kode_kambing" name="kode_kambing" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_kambing">Nama Kambing</label>
                        <input type="text" class="form-control" id="edit_nama_kambing" name="nama_kambing" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_id">Jenis Kambing</label>
                        <select class="form-control" id="edit_jenis_id" name="jenis_id" required>
                            <option value="">Pilih Jenis</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Jantan">Jantan</option>
                            <option value="Betina">Betina</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_tanggal_beli">Tanggal Beli</label>
                        <input type="date" class="form-control" id="edit_tanggal_beli" name="tanggal_beli" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_umur">Umur (bulan)</label>
                        <input type="number" class="form-control" id="edit_umur" name="umur" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_harga_beli">Harga Beli</label>
                        <input type="text" class="form-control" id="edit_harga_beli" name="harga_beli" required onkeyup="formatRupiah(this)">
                    </div>
                    <div class="form-group">
                        <label for="edit_warna">Warna</label>
                        <input type="text" class="form-control" id="edit_warna" name="warna" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_barn_id">Kandang</label>
                        <select class="form-control" id="edit_barn_id" name="barn_id" required>
                            <option value="">Pilih Kandang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_foto">Foto</label>
                        <div id="current_foto" class="mb-2"></div>
                        <input type="file" class="form-control-file" id="edit_foto" name="foto">
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

<!-- Modal Show -->
<div class="modal fade" id="showModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kambing</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
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
                                <th>Jenis</th>
                                <td id="show_jenis"></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td id="show_jenis_kelamin"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Beli</th>
                                <td id="show_tanggal_beli"></td>
                            </tr>
                            <tr>
                                <th>Umur Saat Beli</th>
                                <td id="show_umur"></td>
                            </tr>
                            <tr>
                                <th>Umur Saat Ini</th>
                                <td id="show_umur_saat_ini"></td>
                            </tr>
                            <tr>
                                <th>Harga Beli</th>
                                <td id="show_harga_beli"></td>
                            </tr>
                            <tr>
                                <th>Warna</th>
                                <td id="show_warna"></td>
                            </tr>
                            <tr>
                                <th>Kandang</th>
                                <td id="show_kandang"></td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td id="show_keterangan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div id="show_foto" class="text-center"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Riwayat Kesehatan</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addHealthRecordBtn">
                                <i class="fas fa-plus"></i> Add Catatan Kesehatan
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="health_records_table">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pemeriksaan</th>
                                        <th>Kondisi Kesehatan</th>
                                        <th>Kehamilan</th>
                                        <th>Kondisi</th>
                                        <th>Perawatan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="health_records_body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" id="exportPdfBtn">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Catatan Kesehatan -->
<div class="modal fade" id="addHealthRecordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Catatan Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addHealthRecordForm">
                <div class="modal-body">
                    <input type="hidden" id="health_goat_id" name="goat_id">
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
                            <input type="checkbox" class="custom-control-input" id="kehamilan" name="kehamilan" value="1">
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

<!-- Modal Edit Catatan Kesehatan -->
<div class="modal fade" id="editHealthRecordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Catatan Kesehatan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editHealthRecordForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_health_id" name="id">
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
@endsection

@push('js')
<script>
function formatNumber(value) {
    // Konversi ke integer untuk menghilangkan desimal
    value = parseInt(value);
    // Format angka dengan titik sebagai pemisah ribuan tanpa desimal
    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function formatRupiah(input) {
    // Hapus semua karakter selain angka
    let value = input.value.replace(/\D/g, '');
    
    // Konversi ke integer untuk menghilangkan desimal
    value = parseInt(value);
    
    // Format angka dengan titik sebagai pemisah ribuan
    value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    // Tambahkan prefix Rp
    input.value = 'Rp ' + value;
}

function unformatRupiah(value) {
    // Hapus prefix Rp dan titik, lalu konversi ke integer
    return parseInt(value.replace('Rp ', '').replace(/\./g, ''));
}

function hitungUmurSaatIni(tanggalBeli, umurBulan) {
    const tanggalBeliObj = new Date(tanggalBeli);
    const sekarang = new Date();
    
    // Hitung selisih hari
    const selisihHari = Math.floor((sekarang - tanggalBeliObj) / (1000 * 60 * 60 * 24));
    
    // Hitung total bulan dari umur awal + selisih hari
    const totalBulan = umurBulan + Math.floor(selisihHari / 30);
    const sisaHari = selisihHari % 30;
    
    if (sisaHari === 0) {
        return `${totalBulan} bulan`;
    } else {
        return `${totalBulan} bulan, ${sisaHari} hari`;
    }
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#kambing-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('kambing.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
            {data: 'kode_kambing', name: 'kode_kambing', width: '10%'},
            {data: 'nama_kambing', name: 'nama_kambing', width: '15%'},
            {data: 'jenis_kambing', name: 'jenis_kambing', width: '15%'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin', width: '10%'},
            {data: 'tanggal_beli', name: 'tanggal_beli', width: '10%'},
            {data: 'umur', name: 'umur', width: '10%'},
            {data: 'harga_beli', name: 'harga_beli', width: '15%', render: function(data) {
                return formatNumber(data);
            }},
            {data: 'warna', name: 'warna', width: '10%'},
            {data: 'kandang', name: 'kandang', width: '10%'},
            {data: 'foto', name: 'foto', orderable: false, searchable: false, width: '10%'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: '5%'}
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
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
    $('#addKambingModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
    });

    // Tambah Data
    $('#addForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        // Unformat harga beli sebelum dikirim
        formData.set('harga_beli', unformatRupiah($('#harga_beli').val()));
        $.ajax({
            url: "{{ route('kambing.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addKambingModal').modal('hide');
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
    $(document).on('click', '.show-kambing', function() {
        var id = $(this).data('id');
        $.get("{{ url('kambing') }}/" + id, function(data) {
            $('#show_kode_kambing').text(data.kambing.kode_kambing);
            $('#show_nama_kambing').text(data.kambing.nama_kambing);
            $('#show_jenis').text(data.jenis);
            $('#show_jenis_kelamin').text(data.kambing.jenis_kelamin);
            $('#show_tanggal_beli').text(data.kambing.tanggal_beli);
            $('#show_umur').text(data.kambing.umur + ' bulan');
            $('#show_umur_saat_ini').text(hitungUmurSaatIni(data.kambing.tanggal_beli, data.kambing.umur));
            $('#show_harga_beli').text(formatNumber(data.kambing.harga_beli));
            $('#show_warna').text(data.kambing.warna);
            $('#show_kandang').text(data.kambing.barn_id && data.kambing.barn_id !== null ? (data.kambing.barn ? data.kambing.barn.name : '-') : '-');
            $('#show_keterangan').text(data.kambing.keterangan || '-');
            
            // Update link export PDF
            $('#exportPdfBtn').attr('href', "{{ url('kambing') }}/" + id + "/export-pdf");
            
            if (data.kambing.foto) {
                $('#show_foto').html('<img src="' + data.kambing.foto + '" alt="Foto Kambing" class="img-fluid" style="max-height: 300px;">');
            } else {
                $('#show_foto').html('<p>Tidak ada foto</p>');
            }

            // Tampilkan data kesehatan
            var healthRecordsHtml = '';
            if (data.health_records && data.health_records.length > 0) {
                data.health_records.forEach(function(record) {
                    healthRecordsHtml += '<tr>';
                    healthRecordsHtml += '<td>' + record.checkup_date + '</td>';
                    healthRecordsHtml += '<td>' + record.kondisi_kesehatan + '</td>';
                    healthRecordsHtml += '<td>' + (record.kehamilan ? 'Ya' : 'Tidak') + '</td>';
                    healthRecordsHtml += '<td>' + record.condition + '</td>';
                    healthRecordsHtml += '<td>' + (record.treatment || '-') + '</td>';
                    healthRecordsHtml += '<td>' + (record.notes || '-') + '</td>';
                    healthRecordsHtml += '</tr>';
                });
            } else {
                healthRecordsHtml = '<tr><td colspan="6" class="text-center">Tidak ada data kesehatan</td></tr>';
            }
            $('#health_records_body').html(healthRecordsHtml);

            // Set goat_id untuk form tambah catatan kesehatan
            $('#addHealthRecordBtn').data('goat-id', data.kambing.id);
        });
    });

    // Handle klik tombol Add Catatan Kesehatan
    $('#addHealthRecordBtn').click(function() {
        var goatId = $(this).data('goat-id');
        $('#health_goat_id').val(goatId);
        $('#showModal').modal('hide'); // Sembunyikan modal show kambing
        $('#addHealthRecordModal').modal('show');
    });

    // Submit form tambah catatan kesehatan
    $('#addHealthRecordForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.set('kehamilan', $('#kehamilan').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: "{{ route('health_record.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#addHealthRecordModal').modal('hide');
                toastr.success(response.success);
                // Tampilkan kembali modal show kambing
                $('#showModal').modal('show');
                // Refresh data kambing untuk menampilkan catatan kesehatan terbaru
                var currentGoatId = $('#addHealthRecordBtn').data('goat-id');
                $.get("{{ url('kambing') }}/" + currentGoatId, function(data) {
                    var healthRecordsHtml = '';
                    if (data.health_records && data.health_records.length > 0) {
                        data.health_records.forEach(function(record) {
                            healthRecordsHtml += '<tr>';
                            healthRecordsHtml += '<td>' + record.checkup_date + '</td>';
                            healthRecordsHtml += '<td>' + record.kondisi_kesehatan + '</td>';
                            healthRecordsHtml += '<td>' + (record.kehamilan ? 'Ya' : 'Tidak') + '</td>';
                            healthRecordsHtml += '<td>' + record.condition + '</td>';
                            healthRecordsHtml += '<td>' + (record.treatment || '-') + '</td>';
                            healthRecordsHtml += '<td>' + (record.notes || '-') + '</td>';
                            healthRecordsHtml += '</tr>';
                        });
                    } else {
                        healthRecordsHtml = '<tr><td colspan="6" class="text-center">Tidak ada data kesehatan</td></tr>';
                    }
                    $('#health_records_body').html(healthRecordsHtml);
                });
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

    // Reset form saat modal ditutup
    $('#addHealthRecordModal').on('hidden.bs.modal', function () {
        $('#addHealthRecordForm')[0].reset();
    });

    // Edit Data
    $(document).on('click', '.edit-kambing', function() {
        var id = $(this).data('id');
        $.get("{{ url('kambing') }}/" + id + "/edit", function(data) {
            $('#edit_id').val(data.kambing.id);
            $('#edit_kode_kambing').val(data.kambing.kode_kambing);
            $('#edit_nama_kambing').val(data.kambing.nama_kambing);
            $('#edit_jenis_id').val(data.kambing.jenis_id);
            $('#edit_jenis_kelamin').val(data.kambing.jenis_kelamin);
            $('#edit_tanggal_beli').val(data.kambing.tanggal_beli);
            $('#edit_umur').val(data.kambing.umur);
            $('#edit_harga_beli').val(formatNumber(data.kambing.harga_beli));
            $('#edit_warna').val(data.kambing.warna);
            $('#edit_keterangan').val(data.kambing.keterangan);
            
            // Update dropdown jenis
            var jenisOptions = '<option value="">Pilih Jenis</option>';
            data.jenis.forEach(function(jenis) {
                var selected = (jenis.id == data.kambing.jenis_id) ? 'selected' : '';
                jenisOptions += '<option value="' + jenis.id + '" ' + selected + '>' + jenis.nama_jenis + '</option>';
            });
            $('#edit_jenis_id').html(jenisOptions);
            
            // Update dropdown kandang
            var barnOptions = '<option value="">Pilih Kandang</option>';
            data.barns.forEach(function(barn) {
                var selected = (barn.id == data.kambing.barn_id) ? 'selected' : '';
                barnOptions += '<option value="' + barn.id + '" ' + selected + '>' + barn.name + '</option>';
            });
            $('#edit_barn_id').html(barnOptions);
            
            if (data.kambing.foto) {
                $('#current_foto').html('<img src="' + data.kambing.foto + '" alt="Foto Kambing" style="max-width: 200px;">');
            } else {
                $('#current_foto').html('<p>Tidak ada foto</p>');
            }
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        var formData = new FormData(this);
        formData.append('_method', 'PUT');
        // Unformat harga beli sebelum dikirim
        formData.set('harga_beli', unformatRupiah($('#edit_harga_beli').val()));

        $.ajax({
            url: "{{ url('kambing') }}/" + id,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
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
                    var errorMessage = xhr.responseJSON.error || 'Terjadi kesalahan saat memperbarui data';
                    toastr.error(errorMessage);
                }
            }
        });
    });

    // Hapus Data
    $(document).on('click', '.delete-kambing', function() {
        if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('kambing') }}/" + id,
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

    // Show Add Modal
    $('#addKambingModal').on('show.bs.modal', function() {
        // Load jenis data
        $.ajax({
            url: "{{ route('jenis.getData') }}",
            type: "GET",
            success: function(data) {
                var jenisOptions = '<option value="">Pilih Jenis</option>';
                data.forEach(function(jenis) {
                    jenisOptions += '<option value="' + jenis.id + '">' + jenis.nama_jenis + '</option>';
                });
                $('#jenis_id').html(jenisOptions);
            },
            error: function(xhr) {
                console.error('Error loading jenis data:', xhr);
            }
        });

        // Get next kode kambing
        $.get("{{ route('kambing.getNextKode') }}", function(data) {
            $('#kode_kambing').val(data.kode_kambing);
        });
    });

    // Update JavaScript untuk menampilkan data di form edit
    $(document).on('click', '.edit-health-record', function() {
        var id = $(this).data('id');
        $.get("{{ url('health_record') }}/" + id + "/edit", function(data) {
            $('#edit_health_id').val(data.id);
            $('#edit_checkup_date').val(data.checkup_date);
            $('#edit_condition').val(data.condition);
            $('#edit_treatment').val(data.treatment);
            $('#edit_notes').val(data.notes);
            
            // Set kondisi kesehatan
            if (data.kondisi_kesehatan === 'Sehat') {
                $('#edit_kondisi_sehat').prop('checked', true);
            } else {
                $('#edit_kondisi_sakit').prop('checked', true);
            }
            
            // Set kehamilan
            $('#edit_kehamilan').prop('checked', data.kehamilan);
        });
    });

    // Submit form edit catatan kesehatan
    $('#editHealthRecordForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        // Konversi nilai kehamilan menjadi boolean
        formData = formData.map(function(item) {
            if (item.name === 'kehamilan') {
                item.value = $('#edit_kehamilan').is(':checked') ? '1' : '0';
            }
            return item;
        });
        
        $.ajax({
            url: "{{ url('health_record') }}/" + $('#edit_health_id').val(),
            type: "PUT",
            data: $.param(formData),
            success: function(response) {
                $('#editHealthRecordModal').modal('hide');
                toastr.success(response.success);
                // Refresh data kambing untuk menampilkan catatan kesehatan terbaru
                var currentGoatId = $('#addHealthRecordBtn').data('goat-id');
                $.get("{{ url('kambing') }}/" + currentGoatId, function(data) {
                    var healthRecordsHtml = '';
                    if (data.health_records && data.health_records.length > 0) {
                        data.health_records.forEach(function(record) {
                            healthRecordsHtml += '<tr>';
                            healthRecordsHtml += '<td>' + record.checkup_date + '</td>';
                            healthRecordsHtml += '<td>' + record.kondisi_kesehatan + '</td>';
                            healthRecordsHtml += '<td>' + (record.kehamilan ? 'Ya' : 'Tidak') + '</td>';
                            healthRecordsHtml += '<td>' + record.condition + '</td>';
                            healthRecordsHtml += '<td>' + (record.treatment || '-') + '</td>';
                            healthRecordsHtml += '<td>' + (record.notes || '-') + '</td>';
                            healthRecordsHtml += '</tr>';
                        });
                    } else {
                        healthRecordsHtml = '<tr><td colspan="6" class="text-center">Tidak ada data kesehatan</td></tr>';
                    }
                    $('#health_records_body').html(healthRecordsHtml);
                });
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
});
</script>
@endpush 