<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecAveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_averages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('building_type_id')->unsigned()->nullable();
            $table->foreign('building_type_id')->references('id')->on('building_types')->onDelete('cascade');
            $table->double('sec_avg_small')->nullable();
            $table->double('sec_avg_medium')->nullable();
            $table->double('sec_best_small')->nullable();
            $table->double('sec_best_medium')->nullable();
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
        Schema::dropIfExists('sec_averages');
    }
}
