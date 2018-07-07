<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMainGroupsAddColumnComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_groups', function (Blueprint $table) {
            $table->string('comment')->nullable();
        });
        Schema::table('sec_products', function (Blueprint $table) {
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_groups', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
        Schema::table('sec_products', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
}
