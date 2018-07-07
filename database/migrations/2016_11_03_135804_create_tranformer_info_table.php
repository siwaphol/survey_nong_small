<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranformerInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tranformer_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('electric_user_no');
            $table->string('elec_meter_no');
            $table->integer('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains');
            $table->integer('elec_use_type')->unsigned();
            $table->foreign('elec_use_type')->references('id')->on('elec_use_types');
            $table->string('electric_ratio');
            $table->string('tranformer_power');
            $table->string('amount');
            
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
