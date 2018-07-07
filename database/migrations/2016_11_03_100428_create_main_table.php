<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('user_id');
            $table->string('user_name');
            $table->string('user_area');
            $table->string('person_name');
            $table->string('place_name');
            $table->string('created_since');
            $table->integer('employee_amount');
            $table->integer('building_amount');
            $table->integer('room_amount');
            $table->integer('bed_amount');
            $table->integer('place_type_id')->unsigned();
            $table->foreign('place_type_id')->references('id')->on('place_types')->onDelete('cascade');
            $table->integer('work_time_id')->unsigned();
            $table->foreign('work_time_id')->references('id')->on('work_times')->onDelete('cascade');
            $table->string('contact_name');
            $table->string('contact_number');
            //$table->foreign('user_id')->reference('id')->on('users');
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
