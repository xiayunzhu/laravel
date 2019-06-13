<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_items', function (Blueprint $table) {
            $table->increments('id')->comment('营销活动明细');
            $table->integer('promo_id')->comment('营销活动ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->decimal('promo_price', 10, 2)->comment('商品优惠价')->nullable();

            $table->string('type', 16)->comment('商品标识-限时折扣区分必选，可选');
            $table->string('status', 16)->comment('状态');
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
        Schema::dropIfExists('promo_items');
    }
}
