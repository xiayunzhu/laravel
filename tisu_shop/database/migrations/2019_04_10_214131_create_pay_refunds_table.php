<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_refunds', function (Blueprint $table) {
            $table->increments('id')->comment('申请退款记录表');
            $table->string('return_code', 16)->comment('返回状态码');
            $table->string('err_code', 32)->comment('错误代码')->nullable();
            $table->string('err_code_des', 128)->comment('错误代码描述')->nullable();
            $table->string('appid', 32)->comment('小程序ID')->nullable();
            $table->string('mch_id', 32)->comment('商户号')->nullable();
            $table->string('nonce_str', 32)->comment('随机字符串')->nullable();
            $table->string('sign', 32)->comment('签名')->nullable();
            $table->string('transaction_id', 32)->comment('微信订单号')->nullable();
            $table->string('out_trade_no', 32)->comment('商户订单号	')->nullable();
            $table->string('out_refund_no', 64)->comment('商户退款单号')->nullable();
            $table->string('refund_id', 32)->comment('微信退款单号')->nullable();
            $table->integer('refund_fee')->comment('退款总金额,单位为分,可以做部分退款')->nullable();
            $table->integer('settlement_refund_fee')->comment('应结退款金额')->nullable();
            $table->integer('total_fee')->comment('标价金额')->nullable();
            $table->integer('settlement_total_fee')->comment('应结订单金额')->nullable();
            $table->string('fee_type', 16)->comment('标价币种')->nullable();
            $table->integer('cash_fee')->comment('现金支付金额')->nullable();
            $table->string('cash_fee_type', 16)->comment('现金支付币种')->nullable();
            $table->integer('cash_refund_fee')->comment('现金退款金额')->nullable();
            $table->string('coupon_type_$n', 8)->comment('代金券类型')->nullable();
            $table->integer('coupon_refund_fee')->comment('代金券退款总金额')->nullable();
            $table->integer('coupon_refund_fee_$n')->comment('单个代金券退款金额')->nullable();
            $table->integer('coupon_refund_count')->comment('退款代金券使用数量')->nullable();
            $table->string('coupon_refund_id_$n', 20)->comment('退款代金券ID')->nullable();
            $table->string('return_msg', 128)->comment('返回信息')->nullable();
            $table->timestamps();
            $table->index('transaction_id');
            $table->index('out_trade_no');
            $table->index('out_refund_no');
            $table->index('appid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_refunds');
    }
}
