@extends('layouts.app')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <br>
            <h1>Perpustakaan - Pengembalian</h1>
            <a class="btn btn-success" href="javascript:void(0)" id="createNewPengembalian">Buat Pengembalian</a>
            <br>
            <br/>

            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kembali</th>
                        <th>Kode Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Jatuh Tempo</th>
                        <th>Denda Per Hari</th>
                        <th>Jumlah Hari</th>
                        <th>Total Denda</th>
                        <th>Petugas</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th width="100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        <form id="pengembalianForm" name="pengembalianForm" class="form-horizontal" >
                        <input type="hidden" name="pengembalian_id" id="pengembalian_id">

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Kode Kembali</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="kode_kembali" name="kode_kembali" placeholder="Kode Kembali" value="" maxlength="50" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Kode Pinjam</label>
                                <div class="col-sm-12">
                                    <select name="kode_pinjam" class="form-control" id="kode_pinjam">
                                        <option selected disabled>Pilih Kode Pinjam</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Tanggal Kembali</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control tgl" id="tanggal_kembali" name="tanggal_kembali" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Jatuh Tempo</label>
                                <div class="col-sm-12">
                                    <input type="hidden" name="jatuh_tempo" class="jatuh_tempo" value="">
                                    <input type="date" class="form-control" id="jatuh_tempo" placeholder="Jatuh Tempo" value="" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Petugas</label>
                                    <input type="hidden" name="kode_petugas" class="kode_petugas" value="">
                                    <input type="text" class="form-control" id="kode_petugas" placeholder="Nama Petugas" value="" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Anggota</label>
                                    <input type="hidden" name="kode_anggota" class="kode_anggota" value="">
                                    <input type="text" class="form-control" id="kode_anggota" placeholder="Nama Anggota" value="" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Buku</label>
                                    <input type="hidden" name="kode_buku" class="kode_buku" value="">
                                    <input type="text" class="form-control" id="kode_buku" placeholder="Nama Buku" value="" disabled>
                                </div>
                            </div>

                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan
                                </button>

                                <button type="submit" class="btn btn-danger" id="cancelBtn" value="cancel">Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $("#e1").select2();
    });

    $('#ajaxModel').on('shown.bs.modal',function(){
        $('.tgl').datepicker({
                zIndex: 999999,
                format: 'dd-mm-yyyy',
                autoclose: true,
                changeMonth: true,
                changeYear: true,
        });

        $(document).ready(function(){
            $('.tgl').on("cut paste",function(e) {
            e.preventDefault();
            });
        });

        $('.tgl').keydown(function(e) {
            if (e.keyCode === 8 || e.keyCode === 46) {
            return false;
            }
        });

        $('.tgl').keypress(function(e) {
            return false
        });
    });
</script>
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('pengembalian') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_kembali', name: 'kode_kembali'},
            {data: 'peminjaman.kode_pinjam', name: 'kode_pinjam'},
            {data: 'tanggal_kembali', name: 'tanggal_kembali'},
            {data: 'jatuh_tempo', name: 'jatuh_tempo'},
            {data: 'denda_per_hari', name: 'denda_per_hari'},
            {data: 'jumlah_hari', name: 'jumlah_hari'},
            {data: 'total_denda', name: 'total_denda'},
            {data: 'petugas.nama', name: 'kode_petugas'},
            {data: 'anggota.nama', name: 'kode_anggota'},
            {data: 'buku.judul', name: 'kode_buku'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewPengembalian').click(function () {
        $('#saveBtn').val("create-pengembalian");
        $('#pengembalian_id').val('');
        $('#pengembalianForm').trigger("reset");
        $('#modelHeading').html("Buat Pengembalian");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');
        
    });

     $.ajax({
        url: "{{ url('buku') }}",
        method: "GET",
        dataType: "json",
        success: function (berhasil) {
            console.log(berhasil)
            $.each(berhasil.data, function (key, value) {
                $("#kode_buku").append(
                    `
                        <option value="${value.id}">${value.judul}</option>
                    `
                )
            })
        },
    });

    $.ajax({
        url: "{{ url('anggota') }}",
        method: "GET",
        dataType: "json",
        success: function (berhasil) {
            console.log(berhasil)
            $.each(berhasil.data, function (key, value) {
                $("#kode_anggota").append(
                    `
                        <option value="${value.id}">${value.nama}</option>
                    `
                )
            })
        },
    });

    $.ajax({
        url: "{{ url('petugas') }}",
        method: "GET",
        dataType: "json",
        success: function (berhasil) {
            console.log(berhasil)
            $.each(berhasil.data, function (key, value) {
                $("#kode_petugas").append(
                    `
                        <option value="${value.id}">${value.nama}</option>
                    `
                )
            })
        },
    });

    $.ajax({
        url: "{{ url('peminjaman') }}",
        method: "GET",
        dataType: "json",
        success: function (berhasil) {
            console.log(berhasil)
            $.each(berhasil.data, function (key, value) {
                $("#kode_pinjam").append(
                    `
                        <option value="${value.id}">${value.kode_pinjam}</option>
                    `
                )
            })
        },
    });

    $('body').on('click', '.editPengembalian', function () {
        var pengembalian_id = $(this).data('id');
        $.get("{{ url('pengembalian') }}" +'/' + pengembalian_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Pengembalian");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
            $('#ajaxModel').modal('show');
            $('#pengembalian_id').val(data.kem.id);
            $('#kode_kembali').val(data.kem.kode_kembali);
            $('#kode_pinjam').val(data.kem.kode_pinjam);
            $('#jatuh_tempo').val(data.kem.jatuh_tempo);
            $('#tanggal_kembali').val(data.kem.tanggal_kembali);
            $.each(data.dit, function(key, value){
                console.log(data.dit);
                $('#kode_anggota').val(value.nama_anggota);
                $('#kode_petugas').val(value.nama_petugas);
                $('#kode_buku').val(value.judul);
            });
            $('.alert-danger').html('');
            $('.alert-danger').css('display','none');
        });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');
            $.ajax({
            data: $('#pengembalianForm').serialize(),
            url: "{{ url('pengembalian-store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                Swal.fire({
                    position : 'center',
                    type : 'success',
                    animation : 'false',
                    title : 'Berhasil di Simpan',
                    showConfirmButton : false,
                    timer : 1000,
                    customClass : {
                        popup : 'animated bounceOut'
                    }
                })
                $('#pengembalianForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();

            },
            error: function (request, status, error) {
                $('.alert-danger').html('');
                json = $.parseJSON(request.responseText);
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>'+value+'</p>');
                });
            }
        });
    });

    $('body').on('click', '.deletePengembalian', function () {
        var pengembalian_id = $(this).data("id");
        Swal.fire({
        title: 'Apakah Kamu Yakin?',
        text: "Kamu Tidak Dapat Mengembalikannya Lagi!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "DELETE",
                url: "{{ url('pengembalian-store') }}"+'/'+pengembalian_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
            Swal.fire(
            'Hapus!',
            'Berhasil Dihapus.',
            'success'
            )
        }
        })
    });

    $('#cancelBtn').click(function(e) {
        e.preventDefault();
            $('#pengembalianForm').trigger("reset");
            $('#ajaxModel').modal('hide');
        $
    });

    $(function() {
        $('input').keypress(function() {
            $('.alert-danger').css('display','none');
        });
    });

    $('#kode_pinjam').on('change', function(){
        var kode_pinjam = $(this).val();
        $.ajax({
            url : 'pengembalian-db/'+kode_pinjam,
            method : 'get',
            dataType : 'json',
            success : function (berhasil) {
                $.each(berhasil, function(key, value){
                    $('#kode_anggota').val(value.nama_anggota);
                    $('#kode_petugas').val(value.nama_petugas);
                    $('#kode_buku').val(value.judul);
                    $('#jatuh_tempo').val(value.tanggal_kembali);
                    $('.jatuh_tempo').val(value.tanggal_kembali);
                    $('.kode_buku').val(value.id_judul);
                    $('.kode_anggota').val(value.id_anggota);
                    $('.kode_petugas').val(value.id_petugas);
                });
            },
        });
    });
});
</script>
@endsection
