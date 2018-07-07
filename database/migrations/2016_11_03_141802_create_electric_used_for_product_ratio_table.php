<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectricUsedForProductRatioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electric_used_for_product_ratios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produce_time_id')->unsigned();
            $table->foreign('produce_time_id')->references('id')->on('produce_times');
            /*$table->integer('energy_and_juristic_id')->unsigned();
            $table->foreign('energy_and_juristic_id')->references('id')->on('energy_and_juristics');*/
            $table->string('kwhr_yr');
            $table->string('persentage');
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
        //
    }
}
