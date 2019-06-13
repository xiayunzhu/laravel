<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->coment('登录者的ID');
            $table->string('path')->comment('路由路径');
            $table->string('method')->comment('请求方式');
            $table->string('ip')->comment('访问IP');
            $table->string('sql')->comment('执行的SQL')->nullable();
            $table->text('input')->comment('数据包');
            $table->timestamps();
            $table->index('uid');
            $table->index('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_logs');
    }
}
