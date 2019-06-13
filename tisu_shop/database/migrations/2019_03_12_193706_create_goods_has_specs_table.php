<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsHasSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_has_specs', function (Blueprint $table) {
            $table->increments('id')->comment('商品参数表');//红人选款时,复制自 org_goods_has_specs表
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('spec_id')->comment('规格属性ID');
            $table->integer('spec_value_id')->comment('规格属性值ID');
            $table->integer('shop_id')->comment('店铺ID');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('goods_has_specs');
    }
}
