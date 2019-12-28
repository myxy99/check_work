<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('from_user_id')->unsigned()->comment('发送用户id');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->string('from_user_name', 20)->comment('发送人姓名');
            $table->bigInteger('to_user_id')->unsigned()->comment('接收用户id');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->string('to_user_name', 20)->comment('接收人姓名');
            $table->text('content')->nullable()->comment('发送内容');
            $table->bigInteger('attachment_id')->unsigned()->comment('附件id');
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
        Schema::dropIfExists('chat_records');
    }
}
