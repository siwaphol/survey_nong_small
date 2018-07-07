<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingPerMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_per_months', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_information_id')->unsigned();
            $table->foreign('building_information_id')->references('id')->on('building_informations');
            $table->string('month');
            $table->string('year');
            $table->string('air_conditioned');
            $table->string('non_air_conditioned');
            $table->string('sumspace');
            $table->string('hotel');
            $table->string('hospital');
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
