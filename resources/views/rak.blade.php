@extends('layouts.backend')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
@endsection
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
            <h3 class="box-title">Rak</h3>
            </div>
            <div class="box-body">
                <a class="btn btn-success" href="javascript:void(0)" id="createNewRak"> Buat Rak</a>
                <br>
                <br/>

                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="230px">Kode Rak</th>
                            <th width="230px">Nama Rak</th>
                            <th width="230px">Buku</th>
                            <th>Aksi</th>
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
                                <form id="rakForm" name="rakForm" class="form-horizontal" >
                                <input type="hidden" name="rak_id" id="rak_id">

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Kode Rak</label>
                                            <input type="text" class="form-control" id="kode_rak" name="kode_rak" placeholder="Masukan Kode Rak" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Nama Rak</label>
                                            <input type="text" class="form-control" id="nama_rak" name="nama_rak" placeholder="Masukan Nama Rak" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Buku</label>
                                            <select name="buku[]" class="buku" id="buku" style="width:100%" multiple="multiple"></select>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $("#buku").select2();
    });
</script>
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#buku').select2({
        maximumSelectionLength : 4,
        tags : true
    })

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/rak') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_rak', name: 'kode_rak'},
            {data: 'nama_rak', name: 'nama_rak'},
            {data: 'buku[].judul', render :  function(judul){
                return `${judul}`;
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewRak').click(function () {
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false})
        $('#saveBtn').val("create-rak");
        $('#rak_id').val('');
        $('#rakForm').trigger("reset");
        $('#buku').val('').trigger('change');
        $('#modelHeading').html("Buat Rak");
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
                $("#buku").append(
                    `
                    <option value="${value.id}">
                        ${value.judul}
                    </option>
                    `
                )
            })
        },
        // (id_buku[key] == value.id ? 'selected' : '')
        error: function () {
            console.log('data tidak ada');
        }
    });

    $('body').on('click', '.editRak', function () {
        var rak_id = $(this).data('id');
        $.get("{{ url('/rak') }}" +'/' + rak_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Anggota");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
            $('#ajaxModel').modal('show');
            $('#rak_id').val(data.rak.id);
            $('#kode_rak').val(data.rak.kode_rak);
            $('#nama_rak').val(data.rak.nama_rak);
            $('.alert-danger').html('');
            $('#buku').html('');
            $('#buku').html(data.buku);
            $('.alert-danger').css('display','none');
        })
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');
            $.ajax({
            data: $('#rakForm').serialize(),
            url: "{{ url('rak-store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#rakForm').trigger("reset");
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

    $('body').on('click', '.deleteRak', function () {
        var rak_id = $(this).data("id");
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
                url: "{{ url('rak-store') }}"+'/'+rak_id,
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
            $('#rakForm').trigger("reset");
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
