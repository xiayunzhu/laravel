<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_datas', function (Blueprint $table) {
            $table->increments('id')->comment('每日营业额数据-按天存');
            $table->integer('shop_id')->comment('店铺id');

            $table->integer('turnover_total')->comment('总营业额-付款金额');
            $table->integer('turnover_top')->comment('单笔最高营业额');

            $table->integer('order_total')->comment('下单总金额');

            $table->integer('buyer_order')->comment('下单人数');
            $table->integer('buyer_pay')->comment('付款人数');

            $table->integer('order_count')->comment('下单单量');
            $table->integer('order_pay')->comment('付款单量');
            $table->integer('order_send')->comment('发货单量');

            $table->integer('page_view')->comment('浏览量')->default(0);

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
        Schema::dropIfExists('order_datas');
    }
}
