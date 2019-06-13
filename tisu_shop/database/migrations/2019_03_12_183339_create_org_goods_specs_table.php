<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodsSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_goods_specs', function (Blueprint $table) {
            $table->increments('id')->comment('原始商品规格信息');
            $table->integer('org_goods_id')->comment('org商品ID')->default(0);
            $table->string('org_goods_no', 100)->comment('商品编号');
            $table->decimal('cost', 10, 2)->comment('成本价')->nullable()->default(0);
            $table->decimal('org_goods_price', 10, 2)->comment('商品价格-分销价格')->default(0);
            $table->decimal('line_price', 10, 2)->comment('商品划线价格')->default(0);
            $table->decimal('retail_price', 10, 2)->comment('建议零售价')->nullable()->default(0);
            $table->integer('quantity')->comment('库存')->default(0);// 库存
            $table->integer('virtual_quantity')->comment('虚拟库存')->default(0);// 卖家(红人)设置库存不能大于此虚拟库存
            $table->integer('quantity_offset')->comment('库存偏移量')->nullable()->default(0);
            $table->integer('virtual_sold_num')->comment('虚拟销量')->nullable()->default(0);
            $table->integer('sold_num')->comment('销售数量')->nullable()->default(0);
            $table->string('barcode', 32)->comment('商品条码')->nullable();
            $table->double('weight')->comment('商品重量KG')->nullable()->default(0);
            $table->string("spec_name", 32)->comment('规格名称')->nullable();//颜色+尺码 混编
            $table->string('publish_status', 16)->comment('发布状态')->nullable()->default(\App\Models\OrgGood::PUBLISH_STATUS_UPPER);
            $table->string("item_code", 64)->comment('商品编码')->default('');
            $table->string('spec_code', 64)->comment('ERP规格编码')->default('');
            $table->string('image_url', 255)->comment('商品图片地址')->nullable();
            $table->decimal('commission_rate', 8, 2)->comment("佣金比例(%)")->nullable()->default(0);
            $table->string('price_change', 16)->comment('改价权限')->nullable()->default(\App\Models\OrgGoodsSpec::PRICE_CHANGE_YES);// 改价权限  YES允许  NO不允许
            $table->timestamps();
            $table->softDeletes();
            $table->index('org_goods_id', 'idx_org_specs_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_goods_specs');
    }
}
