<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElectricUsedPerYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electric_used_per_years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tranformer_info_id')->unsigned();
            $table->foreign('tranformer_info_id')->references('id')->on('tranformer_infos');
            $table->string('year');
            $table->string('month');
            $table->string('on_peak');
            $table->string('off_peak');
            $table->string('holiday');
            $table->string('cost_need');
            $table->string('power_used');
            $table->string('cost_true');
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
        Schema::dropIfExists('electric_used_per_years');
    }
}
