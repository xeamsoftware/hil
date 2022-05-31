<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->binary('current_address1')->nullable();      
            $table->binary('current_address2')->nullable();
            $table->string('current_address_city',100)->nullable();      
            $table->string('current_address_pin',50)->nullable();      
            $table->binary('permanent_address1')->nullable();      
            $table->binary('permanent_address2')->nullable();
            $table->string('permanent_address_city',100)->nullable();      
            $table->string('permanent_address_pin',50)->nullable();      
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
        Schema::dropIfExists('user_addresses');
    }
}
