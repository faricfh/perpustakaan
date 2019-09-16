<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Buku;
use DataTables;
use Session;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Buku::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editBuku">Edit</a>';
                    if ($row->rak->count() == 0) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteBuku">Hapus</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('buku');
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
            'kode_buku' => 'required|min:4|max:10|unique:bukus,kode_buku,' . $request->buku_id . ',id',
            'judul' => 'required',
            'penerbit' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required'
        ], [
            'kode_buku.required' => 'Kode Buku Harus di Isi',
            'kode_buku.max' => 'Kode Buku Harus di Isi Maksimal 10',
            'kode_buku.min' => 'Kode Buku Harus di Isi Maksimal 4',
            'kode_buku.unique' => 'Kode Buku Sudah Digunakan',
            'judul.required' => 'judul Harus di Isi',
            'penerbit.required' => 'Judul Harus di Isi',
            'penulis.required' => 'Penulis Harus di Isi',
            'tahun_terbit.required' => 'Tahun Terbit Harus di Pilih'
        ]);

        Buku::updateOrCreate(
            ['id' => $request->buku_id],
            [
                'kode_buku' => $request->kode_buku,
                'judul' => $request->judul,
                'penerbit' => $request->penerbit,
                'penulis' => $request->penulis,
                'tahun_terbit' => $request->tahun_terbit
            ]
        );
        return response()->json(['success' => 'Buku saved successfully.']);
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
        $buku = Buku::find($id);
        return response()->json($buku);
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
        Buku::find($id)->delete();
        return response()->json(['success' => 'Buku deleted successfully.']);
    }
}
