<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_promos', function (Blueprint $table) {
            $table->increments('id')->comment('店铺报名参加的活动');
            $table->integer('shop_id')->comment('店铺id');
            $table->integer('promo_id')->comment('营销活动ID');
            $table->integer('total_count')->comment('发放总量')->nullable()->default(0);
            $table->integer('take_count')->comment('已领总量')->nullable()->default(0);
            $table->integer('used_count')->comment('已使用总量')->nullable()->default(0);
            $table->string('is_preheat', 16)->comment('是否预热')->nullable();
            $table->integer('preheat_begin')->comment('预热开始时间')->nullable();
            $table->integer('preheat_end')->comment('预热结束时间')->nullable();

            $table->string('status', 16)->comment('状态');

            $table->timestamps();
            $table->index('shop_id');
            $table->index('promo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_promos');
    }
}
