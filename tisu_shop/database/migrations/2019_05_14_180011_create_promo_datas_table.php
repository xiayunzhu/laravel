<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_datas', function (Blueprint $table) {
            $table->increments('id')->comment('优惠券数据统计');

            $table->integer('shop_id')->comment('店铺id');
            $table->integer('promo_id')->comment('优惠券ID');
            $table->string('channel')->comment('渠道');

            $table->integer('total_count')->comment('优惠券总量');

            $table->integer('promo_take')->comment('当天领取数量');
            $table->integer('promo_use')->comment('当天使用数量');
            $table->integer('customer_old')->comment('当天用券老客户数');
            $table->integer('customer_new')->comment('当天用券新客数');

            $table->integer('goods_count')->comment('当天购买商品件数');

            $table->integer('order_fee')->comment('当天用券总成交额-付款金额');
            $table->integer('order_count')->comment('当天订单数');
            $table->integer('promo_fee')->comment('当天优惠总金额');

            $table->integer('time')->comment('日期');

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
        Schema::dropIfExists('promo_datas');
    }
}
