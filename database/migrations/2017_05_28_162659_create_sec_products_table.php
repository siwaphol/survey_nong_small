<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_group_id')->unsigned();
            $table->foreign('main_group_id')->references('id')->on('main_groups')->onDelete('cascade');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('building_type_id')->unsigned()->nullable();
            $table->foreign('building_type_id')->references('id')->on('building_types')->onDelete('cascade');
            $table->string('group')->nullable();
            $table->double('elect_mj')->nullable();
            $table->double('energy_mj')->nullable();
            $table->double('total_mj')->nullable();
            $table->double('product_amount')->nullable();
            $table->double('sec')->nullable();
            $table->string('sec_unit')->nullable();
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
        Schema::dropIfExists('sec_products');
    }
}
