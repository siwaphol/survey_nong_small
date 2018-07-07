<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPermonthInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_permonths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('produce_time_id')->unsigned();
            $table->foreign('produce_time_id')->references('id')->on('produce_times');
            $table->string('month');
            $table->string('year');
            $table->integer('amount');
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
