<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNamaPembayarOnPembayaranDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_detail', function (Blueprint $table) {
            $table->dropColumn('nama_pembayar');
        });

        Schema::table('pembayaran_detail', function (Blueprint $table) {
            $table->string('nama_pembayar')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_detail', function (Blueprint $table) {
            //
        });
    }
}
