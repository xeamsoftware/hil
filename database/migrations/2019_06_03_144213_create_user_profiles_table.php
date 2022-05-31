<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');

            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                  ->references('id')->on('departments')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('designation_id');
            $table->foreign('designation_id')
                  ->references('id')->on('designations')
                  ->onDelete('cascade');      

            $table->string('father_name',100)->nullable();
            $table->string('mother_name',100)->nullable();
            $table->date('joining_date');
            $table->date('birth_date')->nullable();
            $table->string('gender',100);           //Male, Female     
            $table->string('marital_status',100);   //Married, Unmarried, Widowed, Divorced  
            $table->string('spouse_name',100)->nullable(); 
            $table->string('emergency_contact_name',100)->nullable();
            $table->string('emergency_contact_number',100)->nullable();
            $table->string('relationship',100)->nullable()->comment('with the emergency contact');
            $table->string('pan_number',100)->nullable();
            $table->string('adhaar_number',100)->nullable();
            $table->string('blood_group',50)->nullable();
            $table->string('vehicle_number',100)->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
