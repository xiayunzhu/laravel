<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_goods', function (Blueprint $table) {
            $table->increments('id')->comment('原始商品表');//运营编辑的商品表
            $table->string('name', 64)->comment('商品名称');
            $table->string('title', 64)->comment('商品标题');
            $table->string("item_code", 64)->comment('商品编码')->default('');
            $table->integer('brand_id')->comment('品牌ID')->nullable();
            $table->integer('category_id')->comment('类目ID');
            $table->tinyInteger('spec_type')->comment('规格类型');//单规格 多规格
            $table->tinyInteger('deduct_stock_type')->comment('扣减库存的方式')->nullable()->default(1);//1-下单减库存 2-支付减库存
            $table->text('content')->comment('商品详情')->nullable();//JSON [ url1,url2,ul3]
            $table->string('introduction',255)->comment('商品简介')->nullable()->default('');
            $table->integer('sales_initial')->comment('初始销量')->default(0);//初始销量
            $table->integer('sales_actual')->comment('实际销售')->nullable()->default(0);//实际销售
            $table->integer('goods_sort')->comment('商品排序')->nullable()->default(100);//排序：数字越小越靠前
            $table->integer('delivery_id')->comment('运费模版')->nullable()->default(0);//运费模板
            $table->string('sales_status', 16)->comment('商品状态 - SOLD_OUT:售罄,ON_SALE:在售, PRE_SALE:预售')->nullable()->default(\App\Models\OrgGood::SALE_STATUS_ON_SALE);
            $table->string('publish_status', 16)->comment('发布状态')->default(\App\Models\OrgGood::PUBLISH_STATUS_UPPER);//下架:0 上架:1
            $table->integer('version')->comment('版本号')->nullable()->default(1);// 继承自运营商品
            $table->decimal('commission_rate', 8, 2)->comment("佣金比例(%)")->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('sales_status','idx_org_sales_status');
            $table->index('publish_status','idx_org_publish_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_goods');
    }
}
