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
                        <h1>Perpustakaan - Rak</h1>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewRak"> Create New Rak</a>
                        <br>
                        <br/>

                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="230px">Kode Rak</th>
                                    <th width="230px">Nama Rak</th>
                                    <th width="230px">Kode Buku</th>
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
                                    <form id="rakForm" name="rakForm" class="form-horizontal" >
                                    <input type="hidden" name="rak_id" id="rak_id">

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Rak</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_rak" name="kode_rak" placeholder="Enter Kode Rak" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Nama Rak</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="nama_rak" name="nama_rak" placeholder="Enter Nama Rak" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label>Kode Buku</label>
                                                <select name="kode_buku" class="form-control" id="kode_buku">
                                                </select>
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
        ajax: "{{ route('rak.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_rak', name: 'kode_rak'},
            {data: 'nama_rak', name: 'nama_rak'},
            {data: 'buku.kode_buku', name: 'kode_buku'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewRak').click(function () {
        $('#saveBtn').val("create-rak");
        $('#rak_id').val('');
        $('#rakForm').trigger("reset");
        $('#modelHeading').html("Create New Rak");
        $('#ajaxModel').modal('show');
    });

     $.ajax({
        url: "{{ route('buku.index') }}",
        method: "GET",
        dataType: "json",
        success: function (berhasil) {
            console.log(berhasil)
            $.each(berhasil.data, function (key, value) {
                $("#kode_buku").append(
                    `
                        <option value="${value.id}">${value.kode_buku}</option>
                    `
                )
            })
        },
    });

    $('body').on('click', '.editRak', function () {
    var rak_id = $(this).data('id');
    $.get("{{ route('rak.index') }}" +'/' + rak_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Anggota");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#rak_id').val(data.id);
        $('#kode_rak').val(data.kode_rak);
        $('#nama_rak').val(data.nama_rak);
        $('#kode_buku').val(data.kode_buku);
    })
});

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save Changes');

        $.ajax({
        data: $('#rakForm').serialize(),
        url: "{{ route('rak.store') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {

            $('#rakForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();

        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Save Changes');
        }
    });
    });

    $('body').on('click', '.deleteRak', function () {
        var rak_id = $(this).data("id");
        var del = confirm("Are You sure want to delete !");

        if (del == true) {
            $.ajax({
            type: "DELETE",
            url: "{{ route('rak.store') }}"+'/'+rak_id,
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
