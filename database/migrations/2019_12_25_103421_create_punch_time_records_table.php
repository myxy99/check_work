<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunchTimeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punch_time_records', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键id');
            $table->string('name', 20)->comment('姓名');
            $table->bigInteger('user_id')->unsigned()->comment('用户id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('required_time')->nullable()->comment('应打卡时间');
            $table->timestamp('actual_time')->comment('实际打卡时间');
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
        Schema::dropIfExists('punch_time_records');
    }
}
