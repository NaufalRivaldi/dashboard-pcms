<?php
// ------------------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// ------------------------------------------------------------------------------------
class CreateWilayahTable extends Migration
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
        Schema::create('wilayah', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->string('kode', 100)->unique();
            $table->string('nama');
            $table->boolean('status')->default(1);
            // ------------------------------------------------------------------------
            $table->softDeletes();
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
        Schema::dropIfExists('wilayah');
        // ----------------------------------------------------------------------------
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------