<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliedLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applied_leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('leave_type_id');
            $table->foreign('leave_type_id')->references('id')->on('leave_types');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('from_time',100)->nullable();
            $table->string('to_time',100)->nullable();
            $table->string('number_of_days',50);
            $table->string('weekoffs',150)->nullable()->comment('For workman only');
            $table->string('excluded_dates',150)->nullable();
            $table->string('paid_leaves_count',50);
            $table->string('unpaid_leaves_count',50);
            $table->string('compensatory_leaves_count',50);
            $table->binary('purpose')->nullable();
            $table->binary('address')->nullable();
            $table->enum('final_status', ['0', '1'])->comment('1=Approved, 0=Not Approved');
            $table->enum('status', ['1', '0'])->comment('1=Active, 0=Inactive');
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
        Schema::dropIfExists('applied_leaves');
    }
}
