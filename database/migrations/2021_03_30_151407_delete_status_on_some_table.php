<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteStatusOnSomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('siswa_aktif', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('siswa_aktif_pendidikan', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('siswa_baru', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('siswa_cuti', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('siswa_inaktif', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            //
        });
    }
}
