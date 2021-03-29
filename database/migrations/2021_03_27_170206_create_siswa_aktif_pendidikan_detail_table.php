<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaAktifPendidikanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_aktif_pendidikan_detail', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->integer('jumlah')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('pendidikan_id')->nullable();
            $table->unsignedBigInteger('siswa_aktif_pendidikan_id')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('pendidikan_id')->references('id')->on('pendidikan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('siswa_aktif_pendidikan_id')->references('id')->on('siswa_aktif_pendidikan')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('siswa_aktif_pendidikan_detail');
    }
}
