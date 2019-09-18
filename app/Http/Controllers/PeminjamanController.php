<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Peminjaman;
use DataTables;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::select('SELECT peminjamen.id,kode_pinjam,date_format(tanggal_pinjam,"%d-%m-%Y") AS tanggal_pinjam,
                                        date_format(tanggal_kembali,"%d-%m-%Y") AS tanggal_kembali,pet.nama AS nama_petugas,
                                        ang.nama AS nama_anggota
                                FROM peminjamen
                                LEFT JOIN petugas AS pet ON pet.id = peminjamen.kode_petugas
                                LEFT JOIN anggotas AS ang ON ang.id = peminjamen.kode_anggota');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $pengembalian = \DB::select('SELECT id FROM pengembalians WHERE kode_pinjam = ' . $row->id . '');
                    if ($pengembalian) {
                        $span = '<span class="label label-warning">Dikembalikan</span>';
                        return $span;
                    } else {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPeminjaman">Edit</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePeminjaman">Hapus</a>';
                        return $btn;
                    }
                })
                ->addColumn('buku', function ($row) {
                    $buku = \DB::select('SELECT pb.id ,b.judul,p.kode_pinjam
                                        FROM peminjaman_buku AS pb
                                        LEFT JOIN bukus AS b ON b.id = pb.id_buku
                                        LEFT JOIN peminjamen AS p ON p.id = pb.id_peminjaman
                                        WHERE pb.id_peminjaman =' . $row->id . '');
                    $databuku = '';
                    foreach ($buku as $value) {
                        $databuku .= '<li>' . $value->judul . '</li>';
                    }
                    return $databuku;
                })
                ->rawColumns(['action', 'buku'])
                ->make(true);
        }
        return view('peminjaman');
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
            'kode_pinjam' => 'required|min:4|max:10|unique:peminjamen,kode_pinjam,' . $request->peminjaman_id . ',id',
            'tanggal_pinjam' => 'required',
            'tanggal_kembali' => 'required',
            'kode_petugas' => 'required',
            'kode_anggota' => 'required',
        ], [
            'kode_pinjam.required' => 'Kode Pinjam Harus di Isi',
            'kode_pinjam.unique' => 'Kode Pinjam Sudah Ada',
            'kode_pinjam.max' => 'Kode Pinjam Harus di Isi Maksimal 10',
            'kode_pinjam.min' => 'Kode Pinjam Harus di Isi Maksimal 4',
            'tanggal_pinjam.required' => 'Tanggal Pinjam Harus di Isi',
            'tanggal_kembali.required' => 'Tanggal Kembali Harus di Isi',
            'kode_petugas.required' => 'Kode Petugas Harus di Pilih',
            'kode_anggota.required' => 'Kode Anggota Harus di Pilih',
        ]);

        $pinjam = \Carbon\Carbon::parse($request->tanggal_pinjam)->format("Y-m-d");
        $kembali = \Carbon\Carbon::parse($request->tanggal_kembali)->format("Y-m-d");
        $pinjam = Peminjaman::updateOrCreate(
            ['id' => $request->peminjaman_id],
            [
                'kode_pinjam' => $request->kode_pinjam,
                'tanggal_pinjam' => $pinjam,
                'tanggal_kembali' => $kembali,
                'kode_petugas' => $request->kode_petugas,
                'kode_anggota' => $request->kode_anggota,
            ]
        );
        $pinjam->buku()->sync($request->buku);
        return response()->json(['success' => 'Peminsjaman saved successfully.']);
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
        $peminjaman = \DB::select('SELECT peminjamen.id,kode_pinjam,date_format(tanggal_pinjam,"%d-%m-%Y") AS tanggal_pinjam, date_format(tanggal_kembali,"%d-%m-%Y") AS tanggal_kembali,pet.id AS id_petugas, ang.id AS id_anggota,pet.nama as nama_petugas, ang.nama as nama_anggota
                                FROM peminjamen
                                LEFT JOIN petugas AS pet ON pet.id = peminjamen.kode_petugas
                                LEFT JOIN anggotas AS ang ON ang.id = peminjamen.kode_anggota
                                WHERE peminjamen.id = ' . $id . '');

        $pin = Peminjaman::find($id);
        $datapeminjaman = [
            'id' => $pin->id, 'kode_pinjam' => $pin->kode_pinjam, 'tanggal_pinjam' => $pin->tanggal_pinjam,
            'tanggal_kembali' => $pin->tanggal_kembali, 'kode_petugas' => $pin->kode_petugas, 'kode_anggota' => $pin->kode_anggota
        ];

        $buku = \DB::select('SELECT b.id,b.judul,rb.id_peminjaman
                            FROM bukus AS b
                            left JOIN peminjaman_buku AS rb ON rb.id_buku = b.id
                            AND rb.id_peminjaman=' . $pin->id . '
                            ');

        foreach ($buku as $value) {
            $option[] = '<option value="' . $value->id . '" ' . ($value->id_peminjaman == $pin->id ? 'selected' : '') . '>' . $value->judul . '</option>';
        }

        $test = implode('', $option);

        $data = ['datapeminjaman' => $datapeminjaman, 'buku' => $test, 'peminjaman' => $peminjaman];

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
        Peminjaman::find($id)->delete();
        return response()->json(['success' => 'Peminjaman deleted successfully.']);
    }
}
