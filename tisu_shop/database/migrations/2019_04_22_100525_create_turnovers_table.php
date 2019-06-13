<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnovers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->comment('店铺ID');
            $table->integer('order_id')->comment('订单ID');
            $table->string('order_no','50')->comment('订单编号');
            $table->integer('goods_id')->comment('商品ID');
            $table->string('goods_name')->comment('商品名称');
            $table->integer('num')->comment('商品数量');
            $table->integer('pay_time')->comment('付款时间')->default(0);
            $table->integer('refund_time')->comment('退款时间')->default(0);
            $table->decimal("payment", 10, 2)->comment('明细金额');
            $table->decimal("discount_fee", 10, 2)->comment('优惠金额')->nullable()->default(0);
            $table->string('type')->comment('类型');
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
        Schema::dropIfExists('turnover');
    }
}
