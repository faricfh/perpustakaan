<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Anggota extends Model
{
    protected $fillable = [
        'kode_anggota', 'nama', 'jk', 'jurusan', 'alamat'
    ];

    public function peminjaman()
    {
        return $this->hasMany('App\Peminjaman', 'kode_anggota');
    }

    public function Pengembalian()
    {
        return $this->hasMany('App\Pengembalian', 'kode_anggota');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($anggota) {
            // mengecek apakah peminjaman masih punya anggota
            if ($anggota->peminjaman->count() > 0) {
                //menyiapkan pesan error
                $html = 'anggota tidak bisa dihapus karena masih digunakan oleh peminjaman: ';
                $html .= '<ul>';
                foreach ($anggota->peminjaman as $data) {
                    $html .= "<li>$data->nama_peminjaman<li>";
                }
                $html .= '<ul>';
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => $html
                ]);
                //membatalkan proses penghapusan
                return false;
            }
        });
    }
}
