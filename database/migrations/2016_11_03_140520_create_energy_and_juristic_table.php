<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyAndJuristicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_and_juristics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('et_id')->unsigned();
            $table->foreign('et_id')->references('id')->on('energy_types');
            $table->integer('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains');
            $table->string('bar')->nullable();
            $table->string('celcious')->nullable();
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
