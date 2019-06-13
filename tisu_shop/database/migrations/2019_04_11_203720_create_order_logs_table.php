<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->increments('id')->comment('订单日志');
            $table->string('order_no', 32)->comment('订单日志');
            $table->string('order_status', 16)->comment('订单状态');
            $table->string('content', 255)->comment('日志内容')->nullable();
            $table->integer('user_id')->comment('操作人ID')->default(0);//0 表示系统操作,比如脚本关闭订单等行为
            $table->timestamps();
            $table->index('order_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_logs');
    }
}
