<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnVwSiswaCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vw_siswa_cuti', function (Blueprint $table) {
            $table->dropColumn('tanggal');
            $table->string('bulan')->nullable()->after('id');
            $table->string('tahun')->nullable()->after('bulan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vw_siswa_cuti', function (Blueprint $table) {
            //
        });
    }
}
