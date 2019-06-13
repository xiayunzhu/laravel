<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_datas', function (Blueprint $table) {
            $table->increments('id')->comment('商品流量-按天存');

            $table->integer('shop_id')->comment('店铺id');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('buyer_view')->comment('当天浏览人数');
            $table->integer('buyer_pay')->comment('当天付款人数');

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
        Schema::dropIfExists('goods_datas');
    }
}
