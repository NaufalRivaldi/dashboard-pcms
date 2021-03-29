<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaAktifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_aktif', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('bulan', 2);
            $table->string('tahun', 6);
            $table->boolean('status')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cabang_id');
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('user_id')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('siswa_aktif');
    }
}
