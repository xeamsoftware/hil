<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_code',100);
            $table->string('password',100);
            $table->string('employee_type',100)->nullable(); //Workman, M&S and CMD
            $table->string('first_name',100);
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('official_email',100)->nullable();
            $table->string('personal_email',100)->nullable();
            $table->string('official_mobile_number',100)->nullable();
            $table->string('personal_mobile_number',100)->nullable();
            $table->string('profile_pic',100)->nullable();
            $table->enum('status', ['1', '0'])->comment('1=Active, 0=Inactive');
            $table->enum('approval_status', ['1', '0'])->comment('1=Profile Approved, 0=Not Approved');
            $table->rememberToken();
            $table->string('forgot_password_token',150)->nullable();
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
        Schema::dropIfExists('users');
    }
}
