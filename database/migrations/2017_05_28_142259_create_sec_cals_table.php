<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecCalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_cals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sec_product_id')->unsigned();
            $table->foreign('sec_product_id')->references('id')->on('sec_products')->onDelete('cascade');
            $table->double('sec_cal1');
            $table->double('sec_cal2');
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
        Schema::dropIfExists('sec_cals');
    }
}
