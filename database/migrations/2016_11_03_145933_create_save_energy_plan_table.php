<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaveEnergyPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('save_energy_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains');
            $table->string('plan');
            $table->integer('electric_power_bf');
            $table->integer('kwh_yr_bf');
            $table->integer('bath_yr_bf');
            $table->integer('fuel_kg_yr_bf');
            $table->integer('fuel_bath_yr_bf');
            $table->integer('electric_power_af');
            $table->integer('kwh_yr_af');
            $table->integer('bath_yr_af');
            $table->integer('fuel_kg_yr_af');
            $table->integer('fuel_bath_yr_af');
            //$table->integer('jurustic_person_id')->reference('id');
            $table->string('timing_plan');
            $table->integer('investment');
            $table->integer('payback_time');
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
