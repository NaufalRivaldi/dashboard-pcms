<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVwSiswaAktifPendidikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vw_siswa_aktif_pendidikan', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
            $table->string('cabang')->nullable();
            $table->string('pendidikan')->nullable();
            $table->integer('jumlah')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vw_siswa_aktif_pendidikan');
    }
}
