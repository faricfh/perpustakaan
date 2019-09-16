<?php

namespace App\Http\Controllers;

use App\Petugas;
use Illuminate\Http\Request;
use DataTables;

class PetugasController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {
        if ($request->ajax()) {
            $data = Petugas::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPetugas">Edit</a>';
                    if ($row->peminjaman->count() == 0) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePetugas">Hapus</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('petugas');
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)
    {
        $request->validate([
            'kode_petugas' => 'required|min:4|max:10|unique:petugas,kode_petugas,' . $request->petugas_id . ',id',
            'nama' => 'required',
            'jk' => 'required',
            'jabatan' => 'required',
            'telp' => 'required|max:12',
            'alamat' => 'required'
        ], [
            'kode_petugas.required' => 'Kode Petugas Harus di Isi',
            'kode_petugas.unique' => 'Kode Petugas Sudah Ada',
            'kode_petugas.max' => 'Kode Petugas Harus di Isi Maksimal 10',
            'kode_petugas.min' => 'Kode Petugas Harus di Isi Maksimal 4',
            'nama.required' => 'Nama Harus di isi',
            'jk.required' => 'Jenis Kelamin Harus di Pilih',
            'jabatan.required' => 'Jabatan Harus di isi',
            'telp.required' => 'No Telepon Harus di isi',
            'telp.max' => 'No Telepon Maksimal 12',
            'alamat.required' => 'Alamat Harus di isi'
        ]);

        Petugas::updateOrCreate(
            ['id' => $request->petugas_id],
            [
                'kode_petugas' => $request->kode_petugas,
                'nama' => $request->nama,
                'jk' => $request->jk,
                'jabatan' => $request->jabatan,
                'telp' => $request->telp,
                'alamat' => $request->alamat
            ]
        );
        return response()->json(['success' => '' . $request->nama . ' Berhasil Di Simpan.']);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {
        $petugas = Petugas::find($id);
        return response()->json($petugas);
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\Product  $product

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {
        Petugas::find($id)->delete();
        return response()->json(['success' => 'Petugas deleted successfully.']);
    }
}
