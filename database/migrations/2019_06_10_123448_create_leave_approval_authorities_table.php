<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveApprovalAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_approval_authorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('supervisor_id');   //supervisor_id   
            $table->foreign('supervisor_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('priority',100)->nullable()->comment('2=Dy.HOD, 3=HOD, 4=DGM, 5=GM, 6=CMD');      
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
        Schema::dropIfExists('leave_approval_authorities');
    }
}
