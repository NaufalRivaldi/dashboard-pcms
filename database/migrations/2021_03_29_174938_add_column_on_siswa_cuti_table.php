<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnSiswaCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswa_cuti', function (Blueprint $table) {
            $table->dropColumn('tanggal');
            $table->string('bulan', 2)->after('id');
            $table->string('tahun', 6)->after('bulan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa_cuti', function (Blueprint $table) {
            //
        });
    }
}
