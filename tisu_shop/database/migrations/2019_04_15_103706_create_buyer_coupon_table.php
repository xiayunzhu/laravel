<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_coupon', function (Blueprint $table) {
            $table->increments('id')->comment('客户已领优惠券');
            $table->integer('shop_id')->comment('店铺id');
            $table->integer('buyer_id')->comment('买家ID');
            $table->integer('promo_id')->comment('优惠券ID');
            $table->string('channel')->comment('渠道');
            $table->softDeletes();
            $table->integer('effect_time')->comment('生效时间')->nullable();
            $table->integer('invalid_time')->comment('失效时间')->nullable();
            $table->integer('status')->comment('优惠券状态');//1:未使用 2:已过期 3:已使用

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
        Schema::dropIfExists('buyer_coupon');
    }
}
