<?php
// ------------------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// ------------------------------------------------------------------------------------
class CreatePembayaranTable extends Migration
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
        Schema::create('pembayaran', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('bulan', 2);
            $table->string('tahun', 6);
            $table->enum('type', [1,2]);
            $table->string('nama_pembayar');
            $table->double('nominal')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cabang_id');
            $table->unsignedBigInteger('materi_grade_id')->nullable();
            // ------------------------------------------------------------------------
            $table->timestamps();
            $table->softDeletes();
            // ------------------------------------------------------------------------
            // Set forign key
            // ------------------------------------------------------------------------
            $table->foreign('user_id')->references('id')->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('pembayaran');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------