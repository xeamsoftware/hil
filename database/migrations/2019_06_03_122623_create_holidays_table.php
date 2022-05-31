<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->date('from_date');
            $table->date('to_date');
            $table->binary('description')->nullable();
            $table->unsignedBigInteger('session_id');
            $table->foreign('session_id')
                  ->references('id')->on('sessions')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('holidays');
    }
}
