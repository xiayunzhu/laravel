<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id')->comment('app商品id');
            $table->string('name', 128)->comment('商品名称');
            $table->string('title', 128)->comment('商品标题');
            $table->integer('brand_id')->comment('品牌ID')->nullable();
            $table->integer('category_id')->comment('类目ID');
            $table->tinyInteger('spec_type')->comment('规格类型');//单规格 多规格
            $table->tinyInteger('deduct_stock_type')->comment('扣减库存的方式')->default(1);//1-下单减库存 2-支付减库存
            $table->text('content')->comment('商品详情')->nullable();//JSON [ url1,url2,ul3]
            $table->string('introduction', 255)->comment('商品简介')->nullable()->default('');
            $table->integer('sales_initial')->comment('初始销量')->nullable()->default(0);//初始销量
            $table->integer('sales_actual')->comment('实际销售')->nullable()->default(0);//实际销售
            $table->integer('quantity')->comment('(虚拟)当前库存')->nullable()->default(0);//计算所得
            $table->decimal('goods_price', 10, 2)->comment('商品价格')->default(0);
            $table->integer('goods_sort')->comment('排序')->default(100);//排序：数字越小越靠前
            $table->integer('delivery_id')->comment('运费模版')->nullable();
            $table->string('sales_status', 16)->comment('商品状态')->default(\App\Models\Goods::SALE_STATUS_ON_SALE);
            $table->string('publish_status', 16)->comment('发布状态')->default(\App\Models\Goods::PUBLISH_STATUS_UPPER);
            $table->integer('confirm_status')->comment('确认状态')->default(\App\Models\Goods::CONFIRM_STATUS_DONE);
            $table->integer('version')->comment('版本号')->default(1);// 继承自运营商品
            $table->integer('shop_id')->comment('店铺ID');
            $table->integer('org_goods_id')->comment('原商品ID')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('shop_id');
            $table->index('publish_status');
            $table->index('sales_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
