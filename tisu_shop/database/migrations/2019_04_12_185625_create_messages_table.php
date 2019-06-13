<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->comment('店铺id');
            $table->string('type', 32)->comment('通知类型')->nullable()->default('');
            $table->string('content')->comment('通知内容')->nullable()->default('');
            $table->string('details')->comment('详情-订单号|金额|件数')->nullable()->default('');
            $table->string('status', 16)->comment('消息状态')->nullable()->default('');
            $table->index('shop_id');
            $table->index('status');
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
        Schema::dropIfExists('messages');
    }
}
