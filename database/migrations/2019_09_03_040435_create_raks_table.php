<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_rak');
            $table->string('nama_rak');
            $table->timestamps();
        });

        Schema::create('rak_buku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_rak');
            $table->unsignedInteger('id_buku');
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
        Schema::dropIfExists('raks');
        Schema::dropIfExists('rak_buku');
    }
}
