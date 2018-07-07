<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains')->onDelete('cascade');
            $table->string('type');
            $table->double('elect_kva');
            $table->double('energy_mj');
            $table->string('group');
            $table->integer('province_id')->unsigned()->nullable()->default(0);
            $table->integer('district_id')->unsigned()->nullable()->default(0);
            $table->integer('sub_district_id')->unsigned()->nullable()->default(0);
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
        Schema::dropIfExists('secs');
    }
}
