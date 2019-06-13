<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id')->comment('订单明细表');
            $table->unsignedInteger('order_id');
            $table->string('order_no', 32)->comment('主订单编号');
            $table->string('item_no', 32)->comment('子订单编号');
            $table->integer('goods_id')->comment('商品ID');//商品
            $table->string('goods_name', 255)->comment('商品名称')->nullable();//[考虑作废]
            $table->string('image_url',255)->comment('商品图片地址')->nullable();//[考虑作废]
//            $table->tinyInteger('deduct_stock_type')->comment('扣减库存的方式')->nullable()->default(1);//扣减库存的方式 //[考虑作废]
//            $table->tinyInteger('spec_type')->comment('规格类型')->nullable();//单规格 多规格 //[考虑作废]
            $table->string('spec_code', 64)->comment('规格编码')->nullable()->default('');//[考虑作废]
            $table->integer('goods_spec_id')->comment('商品规格ID')->default(0);//数据安全校验需要
            $table->integer('org_goods_specs_id')->comment('原始商品规格ID')->default(0);//数据安全校验需要
            $table->string('goods_no', 100)->comment('商品编号')->nullable()->default('');//[考虑作废]
            $table->decimal('goods_price', 10, 2)->comment('商品价格')->default(0);
            $table->decimal('line_price', 10, 2)->comment('商品划线价格')->default(0);
            $table->string("spec_name", 64)->comment('规格名称')->nullable();//颜色+尺码 混编
//            $table->double('weight')->comment('商品重量KG')->default(0);
            $table->integer('num')->comment('数量');
            $table->decimal("receivable", 10, 2)->comment('应付金额(line_price*num)');
            $table->decimal("payment", 10, 2)->comment('实付金额(goods_price*num)');
//            $table->integer('buyer_id')->comment('买家id')->default(0);
            $table->integer('user_id')->comment('买家id(User表类型buyer)');
            $table->integer('shop_id')->comment('店铺ID');
            $table->integer('create_time')->comment('创建时间');
            $table->integer("status")->comment('明细状态')->default(\App\Models\OrderItem::STATUS_WAIT);   //0：未创建订单；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；
            $table->integer("has_refund")->comment('是否为退款/退货明细- 0：无退款；1：申请退款；2.同意退款；3：拒绝退款；4：关闭退款；5：完成退款；')->default(\App\Models\OrderItem::HAS_REFUND_UN_REFUND);   //1:正常，2：售后中，3：取消
            $table->decimal('commission_rate', 8, 2)->comment("佣金比例(%)")->default(0);
            $table->timestamps();
            $table->index('order_no', 'idx_items_order_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
