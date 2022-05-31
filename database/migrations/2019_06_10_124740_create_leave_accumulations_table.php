<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveAccumulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_accumulations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('creator_id');  //by default it is 1 (Super Admin)
            $table->foreign('creator_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');      
            $table->unsignedBigInteger('leave_type_id');      
            $table->foreign('leave_type_id')
                  ->references('id')->on('leave_types')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('applied_leave_id')->default(0); 
            $table->integer('yearly_credit_number')->default(1);  //how many times a leave has been credited using cronjob
            $table->enum('status', ['1', '0'])->comment('1=Active, 0=Inactive');   
            $table->string('comment',100)->nullable();  //Credited by cron, Leave Approved, Leave Rejected After Approval, Added Manually 
            $table->string('previous_count',100)->nullable();
            $table->string('total_remaining_count',100)->nullable();            
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
        Schema::dropIfExists('leave_accumulations');
    }
}
