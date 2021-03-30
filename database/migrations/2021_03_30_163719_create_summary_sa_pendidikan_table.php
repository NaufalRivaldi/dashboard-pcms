<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummarySaPendidikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_sa_pendidikan', function (Blueprint $table) {
            // ------------------------------------------------------------------------
            $table->bigIncrements('id');
            // ------------------------------------------------------------------------
            $table->integer('jumlah')->default(0);
            // ------------------------------------------------------------------------
            $table->unsignedBigInteger('pendidikan_id');
            $table->unsignedBigInteger('summary_id');
            // ------------------------------------------------------------------------
            // Set foreign key
            // ------------------------------------------------------------------------
            $table->foreign('pendidikan_id')->references('id')->on('pendidikan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('summary_id')->references('id')->on('summary')->onUpdate('CASCADE')->onDelete('CASCADE');
            // ------------------------------------------------------------------------
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summary_sa_pendidikan');
    }
}
