@extends('layouts.app')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="container">
                        <br>
                        <h1>Perpustakaan - Peminjaman</h1>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewPeminjaman">Buat Peminjaman</a>
                        <br>
                        <br/>

                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pinjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Petugas</th>
                                    <th>Anggota</th>
                                    <th width="200px">Buku</th>
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
                                    <form id="peminjamanForm" name="peminjamanForm" class="form-horizontal" >
                                    <input type="hidden" name="peminjaman_id" id="peminjaman_id">

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Pinjam</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_pinjam" name="kode_pinjam" placeholder="Kode Pinjam" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Tanggal Pinjam</label>
                                            <div class="col-sm-12">
                                                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" placeholder="Tanggal Pinjam" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Tanggal Kembali</label>
                                            <div class="col-sm-12">
                                                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" placeholder="Tanggal Kembali" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Petugas</label>
                                                <select name="kode_petugas" class="form-control" id="kode_petugas">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Anggota</label>
                                                <select name="kode_anggota" class="form-control" id="kode_anggota">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Buku</label>
                                                <select name="kode_buku" class="form-control" id="kode_buku">
                                                </select>
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
        ajax: "{{ url('peminjaman') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_pinjam', name: 'kode_pinjam'},
            {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
            {data: 'tanggal_kembali', name: 'tanggal_kembali'},
            {data: 'petugas.nama', name: 'kode_petugas'},
            {data: 'anggota.nama', name: 'kode_anggota'},
            {data: 'buku.judul', name: 'kode_buku'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewPeminjaman').click(function () {
        $('#saveBtn').val("create-peminjaman");
        $('#peminjaman_id').val('');
        $('#peminjamanForm').trigger("reset");
        $('#modelHeading').html("Buat Peminjaman");
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

    $('body').on('click', '.editPeminjaman', function () {
        var peminjaman_id = $(this).data('id');
        $.get("{{ url('peminjaman') }}" +'/' + peminjaman_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Peminjaman");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#peminjaman_id').val(data.id);
            $('#kode_pinjam').val(data.kode_pinjam);
            $('#tanggal_pinjam').val(data.tanggal_pinjam);
            $('#tanggal_kembali').val(data.tanggal_kembali);
            $('#kode_petugas').val(data.kode_petugas);
            $('#kode_anggota').val(data.kode_anggota);
            $('#kode_buku').val(data.kode_buku);
            $('.alert-danger').html('');
            $('.alert-danger').css('display','none');
        })
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');
            $.ajax({
            data: $('#peminjamanForm').serialize(),
            url: "{{ url('peminjaman-store') }}",
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
                $('#peminjamanForm').trigger("reset");
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

    $('body').on('click', '.deletePeminjaman', function () {
        var peminjaman_id = $(this).data("id");
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
                url: "{{ url('peminjaman-store') }}"+'/'+peminjaman_id,
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
            $('#peminjamanForm').trigger("reset");
            $('#ajaxModel').modal('hide');
        $
    });

    $(function() {
        $('input').keypress(function() {
            $('.alert-danger').css('display','none');
        });
    });
});
</script>
@endsection
