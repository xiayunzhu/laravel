<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->comment('ERP商品库');
            $table->string("item_name", 255)->comment('商品名称');
            $table->string("bar_code", 64)->comment('商品条码')->default('');
            $table->string("spec_code", 64)->comment('规格编码');//T001| T001-1 T001-2 (SKU)
            $table->string("item_code", 64)->comment('商品编码')->default('');
            $table->string("color", 64)->comment('颜色')->nullable()->default('');
            $table->string("other_prop", 64)->comment('其他规格')->nullable();
            $table->decimal("price", 10, 2)->comment('标价')->nullable();
            $table->string("article_number", 64)->comment('货号')->nullable();
            $table->string("unit", 16)->comment('单位')->nullable();
//            $table->string("erp_shop_nick", 32)->comment('ERP店铺')->nullable();
            $table->string("status", 16)->comment('状态')->default('ON_SALE');
            $table->timestamps();

            $table->index('bar_code');
            $table->unique('spec_code');
            $table->index('item_code');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
