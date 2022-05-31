<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaveHalfToAppliedLeaves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applied_leaves', function (Blueprint $table) {
            $table->string('leave_half',50)->nullable()->comment('For CL & HPSL leave types');
            $table->string('pay_status',50)->nullable()->comment('For HPSL leave type: Half-Pay, Full-Pay');
            $table->boolean('encashment_status')->default(false)->comment('1 = Encashed,0 = Leave Taken');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applied_leaves', function (Blueprint $table) {
            $table->dropColumn('leave_half');
        });
    }
}
