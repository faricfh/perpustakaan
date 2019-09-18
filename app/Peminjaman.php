<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $fillable = [
        'kode_pinjam', 'tanggal_pinjam', 'tanggal_kembali', 'kode_petugas', 'kode_anggota'
    ];

    public function petugas()
    {
        return $this->belongsTo('App\Petugas', 'kode_petugas');
    }

    public function anggota()
    {
        return $this->belongsTo('App\Anggota', 'kode_anggota');
    }

    public function buku()
    {
        return $this->belongsToMany('App\Buku', 'peminjaman_buku', 'id_peminjaman', 'id_buku');
    }

    public function pengembalian()
    {
        return $this->hasMany('App\Pengembalian', 'kode_pinjam');
    }
}
