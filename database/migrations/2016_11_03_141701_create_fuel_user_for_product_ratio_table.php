<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelUserForProductRatioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_user_for_product_ratios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produce_time_id')->unsigned();
            $table->foreign('produce_time_id')->references('id')->on('produce_times');
            $table->integer('energy_type_id')->unsigned();
            $table->foreign('energy_type_id')->references('id')->on('energy_types');
            /*$table->integer('ej_id')->unsigned();
            $table->foreign('ej_id')->references('id')->on('energy_and_juristics');*/
            $table->string('mega_jul_yr');
            $table->string('percentage');
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
