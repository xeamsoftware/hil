<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('old_unit_id');
            $table->foreign('old_unit_id')->references('id')->on('units');  
            $table->unsignedBigInteger('new_unit_id');
            $table->foreign('new_unit_id')->references('id')->on('units');
            $table->unsignedBigInteger('old_role_id');                
            $table->unsignedBigInteger('new_role_id');
            $table->unsignedBigInteger('old_department_id');                
            $table->unsignedBigInteger('new_department_id');
            $table->unsignedBigInteger('old_designation_id');                
            $table->unsignedBigInteger('new_designation_id');
            $table->string('old_supervisor_id',50)->nullable();                
            $table->string('new_supervisor_id',50)->nullable(); 
            $table->string('old_other_supervisor_id',50)->nullable();                
            $table->string('new_other_supervisor_id',50)->nullable(); 
            $table->string('old_deputyhod_id',50)->nullable();                
            $table->string('new_deputyhod_id',50)->nullable();
            $table->string('old_hod_id',50)->nullable(); 
            $table->string('new_hod_id',50)->nullable();               
            $table->string('old_dgm_id',50)->nullable(); 
            $table->string('new_dgm_id',50)->nullable();
            $table->string('old_gm_id',50)->nullable(); 
            $table->string('new_gm_id',50)->nullable();
            $table->string('old_cmd_id',50)->nullable(); 
            $table->string('new_cmd_id',50)->nullable();
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
        Schema::dropIfExists('user_transfers');
    }
}
