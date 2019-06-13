<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID')->nullable();
            $table->string('user_name')->comment('用户昵称')->nullable();
            $table->string('ip', 64)->comment('登录IP')->nullable();
            $table->integer('login_time')->comment('登录时间')->nullable();
            $table->string('address')->comment('登录地址')->nullable();
            $table->string('device')->comment('设备名称')->nullable();
            $table->string('browser')->comment('浏览器')->nullable();
            $table->string('platform')->comment('操作系统')->nullable();
            $table->string('language')->comment('语言')->nullable();
            $table->string('device_type')->comment('设备类型')->nullable();//tablet：平板，mobile：便捷设备，robot：爬虫机器人，desktop：桌面设备
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
        Schema::dropIfExists('login_logs');
    }
}
