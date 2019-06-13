<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->increments('id')->comment('收藏夹');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('buyer_id')->comment('买家ID');
            $table->integer('shop_id')->comment('商城对应店铺ID');
            $table->softDeletes();
            $table->timestamps();
            $table->index('buyer_id','idx_fc_buyer_id');
            $table->index('goods_id','idx_fv_goods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
