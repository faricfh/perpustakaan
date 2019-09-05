<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Anggota;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::all();

        $response = [
            'success' => true,
            'data' => $anggota,
            'message' => 'Berhasil.'
        ];
        return response()->json($response, 200);
    }
}
