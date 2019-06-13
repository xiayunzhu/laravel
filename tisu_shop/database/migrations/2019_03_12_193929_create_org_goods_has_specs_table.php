<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodsHasSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_goods_has_specs', function (Blueprint $table) {
            $table->increments('id')->comment('原始的商品参数表');
            $table->integer('org_goods_id')->comment('商品ID');
            $table->integer('spec_id')->comment('规格属性ID');
            $table->integer('spec_value_id')->comment('规格属性值ID');
            $table->timestamps();
            $table->index('org_goods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_goods_has_specs');
    }
}
