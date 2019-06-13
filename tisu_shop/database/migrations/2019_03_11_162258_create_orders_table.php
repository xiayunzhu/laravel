<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->comment('订单表');
            $table->string('order_no', 32)->comment('主订单编号');
            $table->string("shop_name", 32)->comment('店铺名称');
            $table->string("shop_nick", 32)->comment('店铺昵称');
            $table->string("source", 32)->comment('来源');//wetChat-微信 公众号  PC-商品
            $table->decimal('total_fee', 10, 2)->comment('商品总金额');
            $table->decimal("paid_fee", 10, 2)->comment('实际支付金额')->nullable();
            $table->decimal("discount_fee", 10, 2)->comment('优惠金额')->nullable()->default(0);
            $table->decimal("post_fee", 10, 2)->comment('实际邮费')->nullable()->default(0);
            $table->decimal("service_fee", 10, 2)->comment('服务费')->nullable()->default(0);
            $table->integer('promo_id')->comment('营销活动ID');
            $table->string('channel')->comment('渠道');
            $table->tinyInteger('pay_status')->comment('付款状态');
            $table->integer('pay_time')->comment('付款时间')->default(0);
            $table->decimal('express_price', 10, 2)->comment('运费')->default(0);
            $table->string('express_company', 32)->comment('快递公司')->default('');//SF STO
            $table->string('express_no', 64)->comment('快递单号')->default('');
            $table->tinyInteger('send_status')->comment('发货状态')->default(0);
            $table->integer('send_time')->comment('发货时间')->default(0);
            $table->tinyInteger('receipt_status')->comment('收货状态')->default(0);
            $table->integer('receipt_time')->comment('收货时间')->default(0);
            $table->tinyInteger("refund_status")->comment('退款状态')->default(0);   //0：无退款，1：有退款
            $table->string('order_status', 16)->comment('订单状态')->default('WAIT');//订单查询使用-加索引
            $table->integer('order_type')->comment('订单类型')->default(0);//0: 普通订单
            $table->integer('close_type')->comment('关闭类型')->default(0);//
            $table->integer('close_time')->comment('关闭时间')->default(0);
            $table->integer('create_time')->comment('创建时间')->default(0);
            $table->integer('update_time')->comment('更新时间')->default(0);
            $table->string("buyer_msg", 255)->comment('买家备注')->nullable()->default('');
            $table->string("seller_msg", 255)->comment('卖家备注')->nullable()->default('');
            $table->string("buyer", 32)->comment('买家昵称')->default('');
            $table->integer('user_id')->comment('买家id(User表类型buyer)');
            $table->integer('shop_id')->comment('店铺ID')->default(0);
            $table->timestamps();
            $table->unique('order_no', 'idx_order_no');
            $table->index('shop_id');
            $table->index('user_id');                   //买家id
            $table->index('order_status');              //订单状态
            $table->index('create_time');
            $table->index('refund_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
