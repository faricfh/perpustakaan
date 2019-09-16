@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="container">
                        <br>
                        <h1 align="center">Perpustakaan - Buku</h1>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewBuku">Buat Buku</a>
                        <br>
                        <br/>
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="100px">Kode Buku</th>
                                    <th widh="280px">Judul</th>
                                    <th>Penulis</th>
                                    <th widh="280px">Penerbit</th>
                                    <th widh="50px">Tahun Terbit</th>
                                    <th widh="100px">Aksi</th>
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
                                    <form id="bukuForm" name="bukuForm" class="form-horizontal">
                                    <input type="hidden" name="buku_id" id="buku_id">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Buku</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_buku" name="kode_buku" placeholder="Kode Buku" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Judul</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" placeholder="Judul" value="" maxlength="50" required="">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Penulis</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Penulis" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Penerbit</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Tahun Terbit</label>
                                             <div class="col-sm-12">
                                                <select name="tahun_terbit" class="form-control" id="tahun_terbit">
                                                     <option selected disabled>Pilih Tahun Terbit</option>
                                                     <?php
                                                        for ($x = 1900; $x <= 2020; $x++): ?>
                                                            <option value="<?=$x;?>"><?=$x;?></option><?php
                                                        endfor;
                                                        ?>
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
        ajax: "{{ url('buku') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_buku', name: 'kode_buku'},
            {data: 'judul', name: 'judul'},
            {data: 'penulis', name: 'penulis'},
            {data: 'penerbit', name: 'penerbit'},
            {data: 'tahun_terbit', name: 'tahun_terbit'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewBuku').click(function () {
        $('#saveBtn').val("create-buku");
        $('#buku_id').val('');
        $('#bukuForm').trigger("reset");
        $('#modelHeading').html("Buat Buku");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');
    });

    $('body').on('click', '.editBuku', function () {
    var buku_id = $(this).data('id');
        $.get("{{ url('buku') }}" +'/' + buku_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Buku");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
            $('#ajaxModel').modal('show');
            $('#buku_id').val(data.id);
            $('#kode_buku').val(data.kode_buku);
            $('#judul').val(data.judul);
            $('#penulis').val(data.penulis);
            $('#penerbit').val(data.penerbit);
            $('#tahun_terbit').val(data.tahun_terbit);
            $('.alert-danger').html('');
            $('.alert-danger').css('display','none');
        });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');

        $.ajax({
        data: $('#bukuForm').serialize(),
        url: "{{ url('buku-store') }}",
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
            $('#bukuForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

        },
        error: function (request, status, error) {
                $('.alert-danger').html('');
                json = $.parseJSON(request.responseText);
                console.log(json);
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>'+value+'</p>');
                });
            }
        });
    });

    $('body').on('click', '.deleteBuku', function () {

        var buku_id = $(this).data("id");
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
                url: "{{ url('buku-store') }}"+'/'+buku_id,
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
            $('#bukuForm').trigger("reset");
            $('#ajaxModel').modal('hide');
        $
    })

    $(function() {
        $('input').keypress(function() {
            $('.alert-danger').css('display','none');
        });
    });

});
</script>
@endsection
