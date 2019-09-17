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
            <h3 class="box-title">Petugas</h3>
            </div>
            <div class="box-body">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewPetugas"> Buat Petugas</a>
                    <br>
                    <br>
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="80px">Kode Petugas</th>
                                <th width="100px">Nama</th>
                                <th width="50px">Jenis Kelamin</th>
                                <th width="100px">Jabatan</th>
                                <th width="100px">Telp</th>
                                <th>Alamat</th>
                                <th width="90px">Aksi</th>
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
                                <div id="result"></div>
                                <form id="petugasForm" name="petugasForm" class="form-horizontal">
                                <input type="hidden" name="petugas_id" id="petugas_id">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Kode Petugas</label>
                                            <input type="text" class="form-control" id="kode_petugas" name="kode_petugas" placeholder="Masukan Kode Petugas" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Nama</label>
                                            <input type="text" id="nama" name="nama" placeholder="Masukan Nama" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Jenis Kelamin</label>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input jk" type="radio" id="customRadio1" value="Laki-laki" name="jk">
                                                <label for="customRadio1" class="custom-control-label">Laki-laki</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input jk" type="radio" id="customRadio2" value="Perempuan" name="jk">
                                                <label for="customRadio2" class="custom-control-label">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Jabatan</label>
                                            <input type="text" id="jabatan" name="jabatan" required="" placeholder="Masukan Jabatan" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Telp</label>
                                            <input type="text" id="telp" name="telp" required="" placeholder="Masukan Telpon" class="form-control" maxlength="12">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Alamat</label>
                                            <textarea name="alamat" id="alamat" cols="60" rows="5" class="form-control" placeholder="Masukan Alamat"></textarea>
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

     $("#telp").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('petugas') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_petugas', name: 'kode_petugas'},
            {data: 'nama', name: 'nama'},
            {data: 'jk', name: 'jk'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'telp', name: 'telp'},
            {data: 'alamat', name: 'alamat'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewPetugas').click(function () {
        $('#saveBtn').val("create-petugas");
        $('#petugas_id').val('');
        $('#petugasForm').trigger("reset");
        $('#modelHeading').html("Buat Petugas");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');

    });

    $('body').on('click', '.editPetugas', function () {
    var petugas_id = $(this).data('id');
    $.get("{{ url('petugas') }}" +'/' + petugas_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Petugas");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('#petugas_id').val(data.id);
        $('#kode_petugas').val(data.kode_petugas);
        $('#nama').val(data.nama);
        if(data.jk == 'Laki-laki'){
            $("input[name='jk'][value='Laki-laki']").prop('checked', true);
        }else{
            $("input[name='jk'][value='Perempuan']").prop('checked', true);
        }
        $('#jabatan').val(data.jabatan);
        $('#telp').val(data.telp);
        $('#alamat').val(data.alamat);
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');
        })
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');

        $.ajax({
            data: $('#petugasForm').serialize(),
            url: "{{ url('petugas-store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#petugasForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();
                Swal.fire({
                    position : 'center',
                    type : 'success',
                    animation : 'false',
                    title : data.success,
                    showConfirmButton : false,
                    timer : 1000,
                    customClass : {
                        popup : 'animated bounceOut'
                    }
                });
            },error: function (request, status, error) {
                $('.alert-danger').html('');
                json = $.parseJSON(request.responseText);
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>'+value+'</p>');
                });
            }
        });
    });

    $('body').on('click', '.deletePetugas', function () {
        var petugas_id = $(this).data("id");
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
                url: "{{ url('petugas-store') }}"+'/'+petugas_id,
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
            $('#petugasForm').trigger("reset");
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

