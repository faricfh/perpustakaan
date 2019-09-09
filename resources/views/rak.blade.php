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
                        <h1>Perpustakaan - Rak</h1>
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
                                                <label>Buku</label>
                                                {{-- <div class="col-sm-12"> --}}
                                                    {{-- <select name="kode_buku" class="form-control kode_buku" id="e1" style="width:100%" multiple></select> --}}
                                                {{-- </div> --}}
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
        ajax: "{{ url('/rak') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'kode_rak', name: 'kode_rak'},
            {data: 'nama_rak', name: 'nama_rak'},
            {data: 'buku.judul', name: 'kode_buku'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewRak').click(function () {
        $('#saveBtn').val("create-rak");
        $('#rak_id').val('');
        $('#rakForm').trigger("reset");
        $('#modelHeading').html("Buat Rak");
        $('#ajaxModel').modal('show');
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

    $('body').on('click', '.editRak', function () {
        var rak_id = $(this).data('id');
        $.get("{{ url('/rak') }}" +'/' + rak_id +'/edit', function (data) {
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
        $(this).html('Simpan');
            $.ajax({
            data: $('#rakForm').serialize(),
            url: "{{ url('rak-store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                Swal.fire(
                'Berhasil',
                'Klik OK',
                'success'
                )
                $('#rakForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();

            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Simpan');
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
    })
});
</script>
@endsection
