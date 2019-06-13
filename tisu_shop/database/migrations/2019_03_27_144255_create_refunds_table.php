<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id')->comment('退款明细表');
            $table->string('order_no', 32)->comment('主订单编号');
            $table->string('item_no', 32)->comment('子订单编号');
            $table->integer('user_id')->comment('退款用户ID');
            $table->integer('buyer_id')->comment('买家ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('goods_spec_id')->comment('商品规格ID');
            $table->string('refund_no', 64)->comment('退款订单编号')->default('');
            $table->integer('refund_way')->comment('处理方式');
            $table->integer('refund_reason')->comment('退款原因');
            $table->decimal('back_money', 10, 2)->comment('退款金额');
            $table->string('phone', 32)->comment('手机号码');
            $table->string('remark')->comment("备注")->nullable()->default('');
            $table->text('image_urls')->nullable()->comment('图片地址');
            $table->integer("status")->comment('退款状态')->default(\App\Models\Refund::REFUND_REFUNDING);   //0：申请中；1：已同意；2：已拒绝；3：已关闭
            $table->string("refund_progress", 64)->comment('退款进度')->default(\App\Models\Refund::REFUND_PROGRESS_APPLYING);   //APPLYING：提交申请；SHOP：商家处理；AFTER_SALE：售后处理；SUCCESS：售后成功

            $table->integer('apply_time')->comment('申请时间')->nullable()->default(0);
            $table->integer('seller_audit_time')->comment('卖家审核时间')->nullable()->default(0);
            $table->integer('after_sales_audit_time')->comment('售后审核时间')->nullable()->default(0);
            $table->integer('delivery_time')->comment('买家发货时间')->nullable()->default(0);
            $table->integer('receiving_time')->comment('卖家收货时间')->nullable()->default(0);
            $table->integer('close_time')->comment('关闭时间')->nullable()->default(0);
            $table->string('refuse_reason')->comment("卖家拒绝原因")->nullable()->default('');
            $table->string('close_reason',64)->comment('关闭原因')->nullable()->default('');

            $table->integer('again_apply')->comment('申请次数标识')->nullable()->default(0);    // 0 首次申请  1 二次申请
            $table->integer('delayed_times')->comment('延时收货次数')->nullable()->default(0);    // 运营后台干预  每次叠加3天倒计时

            $table->integer('refund_time')->comment('退款时间')->default(0);
            $table->integer('shop_id')->comment('店铺ID')->default(0);
            $table->timestamps();
            $table->index('order_no');
            $table->index('item_no');
            $table->index('refund_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
