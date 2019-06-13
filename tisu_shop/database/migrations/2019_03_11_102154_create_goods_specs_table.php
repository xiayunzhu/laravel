<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_specs', function (Blueprint $table) {
            $table->increments('id')->comment('商品规格信息');
            $table->integer('goods_id')->comment('商品ID')->default(0);
            $table->string('goods_no', 100)->comment('商品编号')->default(0);
            $table->decimal('goods_price', 10, 2)->comment('商品价格')->default(0);
            $table->decimal('line_price', 10, 2)->comment('商品划线价格')->default(0);
            $table->decimal('fx_price', 10, 2)->comment('分销价格')->default(0);
            $table->decimal('retail_price', 10, 2)->comment('建议零售价')->default(0);
            $table->integer('quantity')->comment('当前库存')->default(0);//计算所得
            $table->integer('virtual_quantity')->comment('虚拟库存')->default(0);
            $table->integer('quantity_offset')->comment('库存偏移量')->default(0);// 虚拟库存 + 库存偏移量 = 当前库存|100 + (-10) = 90
//            $table->integer('sales_num')->comment('销售数量')->default(0);
            $table->integer('virtual_sold_num')->comment('虚拟销量')->default(0);
            $table->integer('sold_num')->comment('销售数量')->default(0);
            $table->string('barcode', 32)->comment('商品条码')->nullable();
            $table->double('weight')->comment('商品重量KG')->default(0);
            $table->string("spec_name", 64)->comment('规格名称')->nullable();//颜色+尺码 混编
            $table->string("color", 32)->comment('颜色')->nullable();//颜色
            $table->string("size", 16)->comment('尺码')->nullable();//尺码
            $table->integer('shop_id')->comment('店铺ID');
            $table->string('sales_status', 16)->comment('商品状态')->default(\App\Models\Goods::SALE_STATUS_ON_SALE);
            $table->string('publish_status', 16)->comment('发布状态')->nullable()->default(\App\Models\Goods::PUBLISH_STATUS_UPPER);
            $table->string('spec_code', 64)->comment('ERP的规格编码')->default('');
            $table->integer('org_goods_specs_id')->comment('原商品specID')->nullable();
            $table->string('image_url', 255)->comment('商品图片地址')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('goods_id', 'idx_goods_id');
            $table->index('shop_id', 'idx_goods_specs_shop_id');
            $table->index('org_goods_specs_id', 'idx_org_goods_specs_id');
        });

        //自定义 规格属性信息 key value 都由运营确定
        // 建议零售价
        // 共享虚拟库存

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_specs');
    }
}
