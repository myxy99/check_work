<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('notice_id')->unsigned()->comment('通知内容表id');
            $table->foreign('notice_id')->references('id')->on('notices');
            $table->bigInteger('user_id')->unsigned()->comment('用户id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('notice_relations');
    }
}
