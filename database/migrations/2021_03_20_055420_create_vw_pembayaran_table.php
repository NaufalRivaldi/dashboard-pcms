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
            $table->string('bulan', 2)->nullable();
            $table->string('tahun', 6)->nullable();
            $table->enum('type', [1,2])->nullable();
            $table->string('nama_pembayar')->nullable();
            $table->double('nominal')->default(0);
            $table->string('cabang')->nullable();
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