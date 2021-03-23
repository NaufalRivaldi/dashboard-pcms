<?php
// ------------------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// ------------------------------------------------------------------------------------
class CreateVwPembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // --------------------------------------------------------------------------------
    public function up()
    {
        Schema::create('vw_pembayaran', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('bulan', 2);
            $table->string('tahun', 6);
            $table->enum('type', [1,2]);
            $table->string('nama_pembayar');
            $table->double('nominal')->default(0);
            $table->string('cabang');
            $table->timestamps();
            // ------------------------------------------------------------------------
        });
    }
    // --------------------------------------------------------------------------------

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // --------------------------------------------------------------------------------
    public function down()
    {
        Schema::create('vw_pembayaran', function (Blueprint $table) {
            Schema::dropIfExists('vw_pembayaran');
        });
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------