<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecMapDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_map_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sec_map_id')->unsigned();
            $table->foreign('sec_map_id')->references('id')->on('sec_maps');
            $table->string('type',3);
            $table->double('potential');
            $table->string('tsic_id',5)->nullable();
            $table->string('tsic_name')->nullable();
            $table->string('bud_type',100)->nullable();
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
        Schema::dropIfExists('sec_map_details');
    }
}
