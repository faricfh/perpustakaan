<?php

namespace App\Http\Controllers;

use App\Pengembalian;
use Illuminate\Http\Request;
use DataTables;
use App\Peminjaman;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengembalian::with('buku', 'petugas', 'anggota', 'peminjaman')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPengembalian">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePengembalian">Hapus</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pengembalian');
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
            'kode_kembali' => 'required|min:4|max:10|unique:pengembalians,kode_kembali,' . $request->pengembalian_id . ',id',
            'kode_pinjam' => 'required',
            'tanggal_kembali' => 'required',
        ], [
            'kode_kembali.required' => 'Kode Kembali Harus di Isi',
            'kode_kembali.max' => 'Kode Kembali Harus di Isi Maksimal 10',
            'kode_kembali.min' => 'Kode Kembali Harus di Isi Maksimal 4',
            'kode_kembali.unique' => 'Kode Kembali Sudah Digunakan',
            'kode_pinjam.required' => 'Kode Pinjam Harus di Pilih',
            'tanggal_kembali.required' => 'Tanggal Kembali Harus di Isi'
        ]);
        $kembali = \Carbon\Carbon::parse($request->tanggal_kembali)->format("Y-m-d");
        $tanggal_kembali = strtotime($request->tanggal_kembali);
        $jatuh_tempo = strtotime($request->jatuh_tempo);
        $jumlah = $tanggal_kembali - $jatuh_tempo;
        $jumlah_hari = floor($jumlah / (60 * 60 * 24));
        if ($jumlah_hari <= 0) {
            $jumlah_hari = 0;
            $total_denda = 0;
        } else {
            $total_denda = $jumlah_hari * 2000;
        }

        Pengembalian::updateOrCreate(
            ['id' => $request->pengembalian_id],
            [
                'kode_kembali' => $request->kode_kembali,
                'kode_pinjam' => $request->kode_pinjam,
                'tanggal_kembali' => $kembali,
                'jatuh_tempo' => $request->jatuh_tempo,
                'denda_per_hari' => 2000,
                'jumlah_hari' => $jumlah_hari,
                'total_denda' => $total_denda,
                'kode_petugas' => $request->kode_petugas,
                'kode_anggota' => $request->kode_anggota,
                'kode_buku' => $request->kode_buku,
            ]
        );
        return response()->json(['success' => 'Pengembalian saved successfully.']);
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
        $pengembalian = Pengembalian::find($id);

        $edit = \DB::select('SELECT pg.id, a.nama AS nama_anggota, p.nama AS nama_petugas, bk.judul, pm.kode_pinjam
                                FROM pengembalians AS pg
                                LEFT JOIN petugas AS p ON p.id = pg.kode_petugas
                                LEFT JOIN anggotas AS a ON a.id = pg.kode_anggota
                                LEFT JOIN bukus AS bk ON bk.id = pg.kode_buku
                                LEFT JOIN peminjamen AS pm ON pm.id = pg.kode_pinjam
                                WHERE pg.id = ' . $id . '');

        $data = ['kem' => $pengembalian, 'dit' => $edit];

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
        Pengembalian::find($id)->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }

    public function db($id)
    {
        $pengembalian = \DB::select('SELECT a.id,a.tanggal_kembali,b.nama AS nama_anggota,c.nama AS nama_petugas, d.judul,b.id AS id_anggota,c.id AS id_petugas,d.id AS id_judul
                                    FROM peminjamen AS a
                                    LEFT JOIN anggotas AS b ON b.id = a.kode_anggota
                                    LEFT JOIN petugas AS c ON c.id = a.kode_petugas
                                    LEFT JOIN bukus AS d ON d.id = a.kode_buku
                                    WHERE a.id = ' . $id . '');
        // dd($pengembalian);
        return response()->json($pengembalian);
    }
}
