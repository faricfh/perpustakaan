<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Anggota;
use DataTables;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editAnggota">Edit</a>';
                    if ($row->peminjaman->count() == 0) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteAnggota">Hapus</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('anggota');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_anggota' => 'required|min:4|max:10|unique:anggotas,kode_anggota,' . $request->anggota_id . ',id',
            'nama' => 'required',
            'jk' => 'required',
            'jurusan' => 'required',
            'alamat' => 'required'
        ], [
            'kode_anggota.required' => 'Kode Anggota Harus di Isi',
            'kode_anggota.max' => 'Kode Anggota Harus di Isi Maksimal 10',
            'kode_anggota.min' => 'Kode Anggota Harus di Isi Maksimal 4',
            'kode_anggota.unique' => 'Kode Anggota Sudah Digunakan',
            'nama.required' => 'Nama Harus di Isi',
            'jk.required' => 'Jenis Kelamin Harus di Pilih',
            'jurusan.required' => 'Jurusan Harus di Isi',
            'alamat.required' => 'Alamat Harus di Pilih'
        ]);

        Anggota::updateOrCreate(
            ['id' => $request->anggota_id],
            [
                'kode_anggota' => $request->kode_anggota,
                'nama' => $request->nama,
                'jk' => $request->jk,
                'jurusan' => $request->jurusan,
                'alamat' => $request->alamat
            ]
        );
        return response()->json(['success' => 'Anggota saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anggota = Anggota::find($id);
        return response()->json($anggota);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Anggota::find($id)->delete();
        return response()->json(['success' => 'Anggota deleted successfully.']);
    }
}
