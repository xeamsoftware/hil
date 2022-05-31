<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompensatoryLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compensatory_leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->date('on_date');   
            $table->string('number_of_hours',50)->nullable();   
            $table->binary('description')->nullable();  
            $table->unsignedBigInteger('selected_supervisor')->default(0);
            $table->unsignedBigInteger('applied_leave_id')->default(0);    
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
        Schema::dropIfExists('compensatory_leaves');
    }
}
