<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesToCompensatoryLeaves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compensatory_leaves', function (Blueprint $table) {
            $table->string('in_time',50)->nullable();
            $table->string('out_time',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensatory_leaves', function (Blueprint $table) {
            $table->dropColumn('in_time');
            $table->dropColumn('out_time');
        });
    }
}
