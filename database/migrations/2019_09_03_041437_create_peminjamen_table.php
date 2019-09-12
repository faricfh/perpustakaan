<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeminjamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->unsignedBigInteger('kode_petugas');
            $table->unsignedBigInteger('kode_anggota');
            $table->unsignedBigInteger('kode_buku');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjamen');
    }
}