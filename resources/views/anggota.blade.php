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
                        <h1>Perpustakaan - Anggota</h1>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewAnggota"> Create New Anggota</a>
                        <br>
                        <br/>

                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Anggota</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jurusan</th>
                                    <th width="280px">Alamat</th>
                                    <th>Action</th>
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
                                    <form id="anggotaForm" name="anggotaForm" class="form-horizontal" >
                                    <input type="hidden" name="anggota_id" id="anggota_id">

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Anggota</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_anggota" name="kode_anggota" placeholder="Enter Kode Anggota" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Nama</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Nama" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Jenis Kelamin</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="jk" name="jk" placeholder="Enter Jenis Kelamin" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Jurusan</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Enter Jurusan" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Alamat</label>
                                            <div class="col-sm-12">
                                                <textarea name="alamat" id="alamat" cols="60" rows="5" class="form-control" placeholder="Enter Alamat"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                                            </button>

                                            <button type="submit" class="btn btn-danger" id="cancelBtn" value="cancel">Cancel
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
        ajax: "{{ route('anggota.index') }}",
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
        $('#saveBtn').val("create-anggota");
        $('#anggota_id').val('');
        $('#anggotaForm').trigger("reset");
        $('#modelHeading').html("Create New Anggota");
        $('#ajaxModel').modal('show');
    });

    $('body').on('click', '.editAnggota', function () {
    var anggota_id = $(this).data('id');
    $.get("{{ route('anggota.index') }}" +'/' + anggota_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Anggota");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#anggota_id').val(data.id);
        $('#kode_anggota').val(data.kode_anggota);
        $('#nama').val(data.nama);
        $('#jk').val(data.jk);
        $('#jurusan').val(data.jurusan);
        $('#alamat').val(data.alamat);
    })
});

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save Changes');

        $.ajax({
        data: $('#anggotaForm').serialize(),
        url: "{{ route('anggota.store') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {

            $('#anggotaForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
    });
    });

    $('body').on('click', '.deleteAnggota', function () {
        var anggota_id = $(this).data("id");
        var del = confirm("Are You sure want to delete !");

        if (del == true) {
            $.ajax({
            type: "DELETE",
            url: "{{ route('anggota.store') }}"+'/'+anggota_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        } else {

        }
        return del;

    });

    $('#cancelBtn').click(function(e) {
        e.preventDefault();
            $('#rakForm').trigger("reset");
            $('#ajaxModel').modal('hide');
        $
    })

});
</script>
@endsection
