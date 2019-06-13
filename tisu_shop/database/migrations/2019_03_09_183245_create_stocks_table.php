<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id')->comment('商品库存');
            $table->timestamp("modified")->comment('库存修改时间');
            $table->Integer("quantity")->comment('实际库存');
            $table->Integer("available")->comment('可用库存');
            $table->string("sku_code", 64)->comment('系统规格编码')->nullable();//spec_code
            $table->string("storage_code", 32)->comment('仓库编码')->nullable()->default('');
            $table->string("storage_name", 32)->comment('仓库名称')->nullable();
            $table->string("item_code", 64)->comment('商品编码')->nullable();
            $table->string("oln_item_id", 64)->comment('线上商品编号，即 B2C 推送到 ERP 中的 itemID')->nullable();
            $table->string("oln_sku_id", 64)->comment('线上规格编号')->nullable();
            $table->timestamps();

            $table->index('sku_code');
            $table->index('item_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
