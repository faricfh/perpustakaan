<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rak;
use App\Buku;
use DataTables;

class RakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Rak::with('buku')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRak">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRak">Hapus</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('rak');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'kode_rak' => 'required|min:4|max:10|unique:raks,kode_rak,' . $request->rak_id . ',id',
            'nama_rak' => 'required',
            'buku' => 'required'
        ], [
            'kode_rak.required' => 'Kode Rak Harus di Isi',
            'kode_rak.max' => 'Kode Rak Harus di Isi Maksimal 10',
            'kode_rak.min' => 'Kode Rak Harus di Isi Maksimal 4',
            'kode_rak.unique' => 'Kode Rak Sudah Digunakan',
            'nama_rak.required' => 'Nama Rak Harus di Isi',
            'buku.required' => 'Buku Harus di Isi'
        ]);

        $rak = Rak::updateOrCreate(
            ['id' => $request->rak_id],
            [
                'kode_rak' => $request->kode_rak,
                'nama_rak' => $request->nama_rak
            ]
        );
        $rak->buku()->sync($request->buku);
        return response()->json(['success' => 'rak saved successfully.']);
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
        $rak = Rak::find($id);
        $data_rak = ['id' => $rak->id, 'nama_rak' => $rak->nama_rak, 'kode_rak' => $rak->kode_rak];
        $buku = \DB::select('SELECT b.id,b.judul,rb.id_rak
                            FROM bukus AS b
                            left JOIN rak_buku AS rb ON rb.id_buku = b.id
                            AND rb.id_rak=' . $rak->id . '
                            ');
        foreach ($buku as $value) {
            $option[] = '<option value="' . $value->id . '" ' . ($value->id_rak == $rak->id ? 'selected' : '') . '>' . $value->judul . '</option>';
        }

        $test = implode('', $option);

        $data = ['datarak' => $data_rak, 'buku' => $test, 'rak' => $rak];

        return response()->json($data);
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
        Rak::find($id)->delete();
        return response()->json(['success' => 'Rak deleted successfully.']);
    }
}
