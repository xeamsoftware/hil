<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveTypeLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_type_limits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_type',100)->nullable(); //Workman, M&S and CMD
            $table->unsignedBigInteger('leave_type_id');      
            $table->foreign('leave_type_id')
                  ->references('id')->on('leave_types')
                  ->onDelete('cascade');
            $table->string('total_upper_limit',100)->nullable();            
            $table->string('max_yearly_limit',100)->nullable();      
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
        Schema::dropIfExists('leave_type_limits');
    }
}
