<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $fillable = [
        'kode_rak', 'nama_rak'
    ];

    public function buku()
    {
        return $this->belongsToMany('App\Buku', 'rak_buku', 'id_rak', 'id_buku');
    }
}
