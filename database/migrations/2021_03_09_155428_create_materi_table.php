<?php
// ------------------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// ------------------------------------------------------------------------------------
class CreateMateriTable extends Migration
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
        Schema::create('materi', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('nama');
            $table->boolean('status')->default(1);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('kategori_id');
            // ------------------------------------------------------------------------
            $table->softDeletes();
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('kategori_id')->references('id')->on('kategori')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        // ----------------------------------------------------------------------------
        Schema::dropIfExists('materi');
        // ----------------------------------------------------------------------------
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------