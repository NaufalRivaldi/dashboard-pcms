<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('bulan', 2);
            $table->string('tahun', 6);
            $table->double('uang_pendaftaran')->default(0);
            $table->double('uang_kursus')->default(0);
            $table->integer('siswa_aktif')->default(0);
            $table->integer('siswa_baru')->default(0);
            $table->integer('siswa_cuti')->default(0);
            $table->integer('siswa_keluar')->default(0);
            $table->boolean('status')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_approve_id')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_approve_id')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('summary');
    }
}
