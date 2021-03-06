<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->binary('message');
            $table->string('label',100)->nullable();
            $table->enum('status', ['1', '0'])->comment('1=Active, 0=Inactive');
            $table->enum('read_status', ['0', '1'])->comment('1=Read, 0=Unread');
            $table->unsignedBigInteger('notificationable_id')->nullable()->comment('model_id');
            $table->string('notificationable_type',100)->nullable()->comment('model_type');
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
        Schema::dropIfExists('notifications');
    }
}
