<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsGroupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_group_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_group_id')->comment('分组ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('shop_id')->comment('店铺ID');
            $table->timestamps();
            $table->index('goods_group_id','index_goods_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_group_items');
    }
}
