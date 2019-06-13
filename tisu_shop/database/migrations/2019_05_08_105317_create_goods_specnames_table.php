<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSpecnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_specnames', function (Blueprint $table) {
            $table->increments('id')->comment('sku规格表');

            $table->integer('org_goods_id')->comment('运营商品ID');
            $table->integer('org_goods_specs_id')->comment('运营SKU ID');
            $table->string('spec_name', 255)->comment('属性名称');
            $table->string('spec_value', 255)->comment('属性值');
            $table->string('status', 16)->comment('状态');

            $table->timestamps();
            $table->index('org_goods_specs_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_specnames');
    }
}
