<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPromoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_promo_items', function (Blueprint $table) {
            $table->increments('id')->comment('店铺报名参加活动的商品信息');
            $table->integer('shop_id')->comment('店铺id');
            $table->integer('promo_id')->comment('营销活动ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->decimal('promo_price', 10, 2)->comment('商品优惠价')->nullable();
            $table->string('type', 16)->comment('商品标识-必选，可选');
            $table->string('status', 16)->comment('状态');

            $table->timestamps();
            $table->index('shop_id');
            $table->index('promo_id');
            $table->index('goods_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_promo_items');
    }
}
