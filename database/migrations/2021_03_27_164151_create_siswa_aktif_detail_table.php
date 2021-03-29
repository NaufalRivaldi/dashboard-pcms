<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaAktifDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_aktif_detail', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->integer('jumlah')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('materi_id')->nullable();
            $table->unsignedBigInteger('siswa_aktif_id')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('materi_id')->references('id')->on('materi')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('siswa_aktif_id')->references('id')->on('siswa_aktif')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('siswa_aktif_detail');
    }
}
