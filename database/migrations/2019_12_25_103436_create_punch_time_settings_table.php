<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunchTimeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punch_time_settings', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键id');
            $table->time('clock_time')->comment('时间');
            $table->timestamps();
            $table->timestamp('unable_at')->nullable()->comment('失效时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('punch_time_settings');
    }
}
