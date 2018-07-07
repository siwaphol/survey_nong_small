<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduceProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produce_processes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produce_time_id')->unsigned();
            $table->foreign('produce_time_id')->references('id')->on('produce_times');
            $table->string('step');
            $table->string('detail');
            $table->string('energy_used');
            $table->string('persentage_used');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table');
    }
}
