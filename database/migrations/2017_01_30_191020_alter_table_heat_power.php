<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHeatPower extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('heat_power_used_for_machines', function (Blueprint $table) {
            $table->text('energy_type')->nullable();
            $table->text('unit_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('heat_power_used_for_machines', function (Blueprint $table) {
            $table->dropColumn('energy_type');
            $table->dropColumn('unit_en');
        });
    }
}
