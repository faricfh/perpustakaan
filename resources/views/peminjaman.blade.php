@extends('layouts.backend')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Perpustakaan
      </h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Peminjaman</h3>
            </div>
            <div class="box-body">
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


                <div class="modal" id="ajaxModel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                            </div>

                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="peminjamanForm" name="peminjamanForm" class="form-horizontal" >
                                    <div class="h">

                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Kode Pinjam</label>
                                            <input type="text" class="form-control" id="kode_pinjam" name="kode_pinjam" placeholder="Kode Pinjam" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Tanggal Pinjam</label>
                                            <input type="text" class="form-control datepicker" id="tanggal_pinjam" name="tanggal_pinjam" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Tanggal Kembali</label>
                                            <input type="text" class="form-control datepicker" id="tanggal_kembali" name="tanggal_kembali" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Petugas</label>
                                            <select name="kode_petugas" class="form-control" id="kode_petugas">
                                                <option selected disabled>Pilih Petugas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Anggota</label>
                                            <select name="kode_anggota" class="form-control" id="kode_anggota">
                                                <option selected disabled>Pilih Anggota</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Buku</label>
                                            <select name="kode_buku" class="form-control" id="kode_buku">
                                                <option selected disabled>Pilih Buku</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan
                                    </button>

                                    <button type="submit" class="btn btn-danger" id="cancelBtn" value="cancel">Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@section('js')
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
            {data: 'nama_petugas', name: 'kode_petugas'},
            {data: 'nama_anggota', name: 'kode_anggota'},
            {data: 'judul', name: 'kode_buku'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewPeminjaman').click(function () {
        $('#saveBtn').val("create-peminjaman");
        $('#peminjaman_id').val('');
        $('#peminjamanForm').trigger("reset");
        $('#modelHeading').html("Buat Peminjaman");
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

    $('body').on('click', '.editPeminjaman', function () {
        var peminjaman_id = $(this).data('id');
        $.get("{{ url('peminjaman') }}" +'/' + peminjaman_id +'/edit', function (data) {
            $.each(data,function(key, value){
                console.log(value);
            $('#modelHeading').html("Edit Peminjaman");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
            $('#ajaxModel').modal('show');
            $('.h').append(
                '<input type="hidden" name="peminjaman_id" id="peminjaman_id" value="'+value.id+'">'
            );
            $('#kode_pinjam').val(value.kode_pinjam);
            $('#tanggal_pinjam').val(value.tanggal_pinjam);
            $('#tanggal_kembali').val(value.tanggal_kembali);
            $('#kode_petugas').val(value.id_petugas);
            $('#kode_anggota').val(value.id_anggota);
            $('#kode_buku').val(value.id_buku);
            $('.alert-danger').html('');
            $('.alert-danger').css('display','none');
            });
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
                $('#peminjamanForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();
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
                });
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
    // $('#ajaxModel').on('shown.bs.modal',function(){
    //     $('.tgl').datepicker({
    //             zIndex: 999999,
    //             format: 'dd-mm-yyyy',
    //             autoclose: true,
    //             changeMonth: true,
    //             changeYear: true,
    //     });

    //     $(document).ready(function(){
    //         $('.tgl').on("cut paste",function(e) {
    //         e.preventDefault();
    //         });
    //     });

    //     $('.tgl').keydown(function(e) {
    //         if (e.keyCode === 8 || e.keyCode === 46) {
    //         return false;
    //         }
    //     });

    //     $('.tgl').keypress(function(e) {
    //         return false
    //     });
    // });
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,

    });
});


</script>
@endsection
