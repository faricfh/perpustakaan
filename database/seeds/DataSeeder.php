<?php

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Petugas::create([
            'kode_petugas' =>  '001PA',
            'nama' =>  'Acep',
            'jk' =>  'Laki-laki',
            'jabatan' =>  'Security',
            'telp' =>  '086475933471',
            'alamat' =>  'Jl Terusan',
        ]);

        App\Petugas::create([
            'kode_petugas' =>  '001PS',
            'nama' =>  'Aceng',
            'jk' =>  'Laki-laki',
            'jabatan' =>  'Security',
            'telp' =>  '0754829946264',
            'alamat' =>  'Jl Bogor',
        ]);

        App\Petugas::create([
            'kode_petugas' =>  '001PD',
            'nama' =>  'Santi',
            'jk' =>  'Perempuan',
            'jabatan' =>  'Bendahara',
            'telp' =>  '087556227775',
            'alamat' =>  'Jl Sunangkas',
        ]);
    }
}
