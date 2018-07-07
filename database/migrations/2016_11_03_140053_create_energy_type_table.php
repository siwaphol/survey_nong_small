<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnergyTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_id');
            $table->string('type_name');
            $table->string('type_group_id');
            $table->string('type_group');
            $table->string('code');
            $table->string('energy_name');
            $table->string('unit_no');
            $table->string('unit');
            $table->string('heat_rate');
            $table->string('standard');
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
