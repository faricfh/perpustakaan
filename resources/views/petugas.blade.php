@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="container">
                        <h1>Perpustakaan - Petugas</h1>
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewPetugas"> Buat Petugas</a>
                        <br>
                        <br>
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Petugas</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jabatan</th>
                                    <th>Telp</th>
                                    <th width="100px">Alamat</th>
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
                                    <form id="petugasForm" name="petugasForm" class="form-horizontal">
                                    <input type="hidden" name="petugas_id" id="petugas_id">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Kode Petugas</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="kode_petugas" name="kode_petugas" placeholder="Enter Kode Petugas" value="" maxlength="50" required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="nama" name="nama" required="" placeholder="Enter Nama" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="jk" name="jk" required="" placeholder="Enter Jenis Kelamin" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jabatan</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="jabatan" name="jabatan" required="" placeholder="Enter Jabaran" class="form-control">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Telp</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="telp" name="telp" required="" placeholder="Enter Telpon" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Alamat</label>
                                            <div class="col-sm-12">
                                                <textarea name="alamat" id="alamat" cols="60" rows="5" class="form-control" placeholder="Enter Alamat"></textarea>
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
        $('#modelHeading').html("Create New Petugas");
        $('#ajaxModel').modal('show');

    });

    $('body').on('click', '.editPetugas', function () {
    var petugas_id = $(this).data('id');
    $.get("{{ url('petugas') }}" +'/' + petugas_id +'/edit', function (data) {
        $('#modelHeading').html("Edit Petugas");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#petugas_id').val(data.id);
        $('#kode_petugas').val(data.kode_petugas);
        $('#nama').val(data.nama);
        $('#jk').val(data.jk);
        $('#jabatan').val(data.jabatan);
        $('#telp').val(data.telp);
        $('#alamat').val(data.alamat);
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
            Swal.fire(
            'Berhasil',
            'Klik OK',
            'success'
            )
            $('#petugasForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Simpan');
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
    })
});
</script>
@endsection

