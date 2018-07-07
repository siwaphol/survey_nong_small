<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyUsedPerYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_used_per_years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('energy_and_juristic_id')->unsigned();
            $table->foreign('energy_and_juristic_id')->references('id')->on('energy_and_juristics');
            $table->string('month');
            $table->string('unit');
            $table->string('cost_unit');
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
