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
            <h3 class="box-title">Anggota</h3>
            </div>
            <div class="box-body">
                <a class="btn btn-success" href="javascript:void(0)" id="createNewAnggota"> Buat Anggota</a>
                <br>
                <br/>

                <table class="table table-bordered data-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Anggota</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th width="150px">Jurusan</th>
                            <th width="280px">Alamat</th>
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
                                <form id="anggotaForm" name="anggotaForm" class="form-horizontal" >
                                <input type="hidden" name="anggota_id" id="anggota_id">

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Kode Anggota</label>
                                            <input type="text" class="form-control" id="kode_anggota" name="kode_anggota" placeholder="Masukan Kode Anggota" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama" required="">
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
                                            <label>Jurusan</label>
                                            <select class="form-control" name="jurusan" id="jurusan">
                                                <option selected disabled>Pilih Jurusan</option>
                                                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                                                <option value="Teknik Sepeda Motor">Teknik Sepeda Motor</option>
                                            </select>
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

                                    <button type="submit" class="btn btn-danger" data-dismiss="modal">Batal
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
        ajax: "{{ url('anggota') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_anggota', name: 'kode_anggota'},
            {data: 'nama', name: 'nama'},
            {data: 'jk', name: 'jk'},
            {data: 'jurusan', name: 'jurusan'},
            {data: 'alamat', name: 'alamat'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewAnggota').click(function () {
        $('#ajaxModel').fadeIn('slow');
        $('#saveBtn').val("create-anggota");
        $('#anggota_id').val('');
        $('#anggotaForm').trigger("reset");
        $('#modelHeading').html("Buat Anggota");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');
    });

    $('body').on('click', '.editAnggota', function () {
    var anggota_id = $(this).data('id');
    $.get("{{ url('anggota') }}" +'/' + anggota_id +'/edit', function (data) {
        $('#ajaxModel').fadeIn('slow');
        $('#modelHeading').html("Edit Anggota");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal({backdrop: 'static', keyboard: false});
        $('#ajaxModel').modal('show');
        $('#anggota_id').val(data.id);
        $('#kode_anggota').val(data.kode_anggota);
        $('#nama').val(data.nama);
        if(data.jk == 'Laki-laki'){
            $("input[name='jk'][value='Laki-laki']").prop('checked', true);
        }else{
            $("input[name='jk'][value='Perempuan']").prop('checked', true);
        }
        $('#jurusan').val(data.jurusan);
        $('#alamat').val(data.alamat);
        $('.alert-danger').html('');
        $('.alert-danger').css('display','none');
    })
});

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');

        $.ajax({
        data: $('#anggotaForm').serialize(),
        url: "{{ url('anggota-store') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {
            $('#anggotaForm').trigger("reset");
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

    $('body').on('click', '.deleteAnggota', function () {
        var anggota_id = $(this).data("id");
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
                url: "{{ url('anggota-store') }}"+'/'+anggota_id,
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

    $(function() {
        $('input').keypress(function() {
            $('.alert-danger').css('display','none');
        });
    });

});
</script>
@endsection
