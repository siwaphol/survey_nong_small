<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeatPowerUsedForMachineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heat_power_used_for_machines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_id')->unsigned();
            $table->foreign('m_id')->references('id')->on('machine_and_main_tools')->onDelete('cascade');;
            $table->string('size');
            $table->string('amount');
            $table->string('life_time');
            $table->string('work_hous');
            $table->string('energy_type');
            $table->string('unit_en');
            $table->string('average_per_year');
            $table->string('persentage');
            $table->string('note');
            $table->integer('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains');
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
