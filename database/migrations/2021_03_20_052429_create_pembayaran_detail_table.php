<?php
// ------------------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// ------------------------------------------------------------------------------------
class CreatePembayaranDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // --------------------------------------------------------------------------------
    public function up()
    {
        // ----------------------------------------------------------------------------
        Schema::create('pembayaran_detail', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->enum('type', [1,2]);
            $table->string('nama_pembayar');
            $table->double('nominal')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('pembayaran_id')->nullable();
            $table->unsignedBigInteger('materi_grade_id')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            // ------------------------------------------------------------------------
            // Set forign key
            // ------------------------------------------------------------------------
            $table->foreign('pembayaran_id')->references('id')->on('pembayaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('materi_grade_id')->references('id')->on('materi_grade')->onUpdate('CASCADE')->onDelete('CASCADE');
            // ------------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------------
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
        Schema::dropIfExists('pembayaran_detail');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------