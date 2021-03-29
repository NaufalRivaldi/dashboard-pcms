<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVwSiswaCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vw_siswa_cuti', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->date('tanggal')->nullable();
            $table->string('cabang')->nullable();
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
        Schema::dropIfExists('vw_siswa_cuti');
    }
}
