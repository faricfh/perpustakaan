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
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewBuku"> Create New Buku</a>
                        <br>
                        <br/>
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Buku</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Terbit</th>
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
                                    <form id="bukuForm" name="bukuForm" class="form-horizontal">
                                    <input type="hidden" name="buku_id" id="buku_id">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Buku</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_buku" name="kode_buku" placeholder="Enter Kode Buku" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Judul</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Enter Judul" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Penulis</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Enter Penulis" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Penerbit</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Enter Penerbit" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Tahun Terbit</label>
                                            <div class="col-sm-12">
                                                <input type="date" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Enter Tahun Terbit" value="" maxlength="50" required="">
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
        ajax: "{{ route('buku.index') }}",
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
        $('#modelHeading').html("Create New Buku");
        $('#ajaxModel').modal('show');
    });

    $('body').on('click', '.editBuku', function () {
    var buku_id = $(this).data('id');
    $.get("{{ route('buku.index') }}" +'/' + buku_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Buku");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#buku_id').val(data.id);
        $('#kode_buku').val(data.kode_buku);
        $('#judul').val(data.judul);
        $('#penulis').val(data.penulis);
        $('#penerbit').val(data.penerbit);
        $('#tahun_terbit').val(data.tahun_terbit);
    })
});

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save Changes');

        $.ajax({
        data: $('#bukuForm').serialize(),
        url: "{{ route('buku.store') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {

            $('#bukuForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
    });
    });

    $('body').on('click', '.deleteBuku', function () {

        var buku_id = $(this).data("id");
        var del = confirm("Are You sure want to delete !");

        if (del == true) {
            $.ajax({
            type: "DELETE",
            url: "{{ route('buku.store') }}"+'/'+buku_id,
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
