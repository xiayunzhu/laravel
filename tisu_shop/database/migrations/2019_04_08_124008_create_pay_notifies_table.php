<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_notifies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid', 32)->nullable()->comment('小程序ID');
            $table->string('mch_id', 32)->nullable()->comment('商户号');
            $table->string('device_info', 32)->nullable()->comment('设备号	');
            $table->string('nonce_str', 32)->nullable()->comment('随机字符串');
            $table->string('sign', 32)->nullable()->comment('签名');
            $table->string('result_code', 16)->nullable()->comment('业务结果');
            $table->string('err_code', 32)->nullable()->comment('错误代码');
            $table->string('err_code_des', 128)->nullable()->comment('错误代码描述	');
            $table->string('openid', 128)->nullable()->comment('用户标识');
            $table->string('is_subscribe', 8)->nullable()->comment('是否关注公众账号,Y-关注，N-未关注');
            $table->string('trade_type', 16)->nullable()->comment('交易类型	');
            $table->string('bank_type', 16)->nullable()->comment('付款银行');
            $table->integer('total_fee')->nullable()->comment('订单总金额，单位为分	');
            $table->integer('settlement_total_fee')->nullable()->comment('应结订单金额');//应结订单金额=订单金额-非充值代金券金额，应结订单金额<=订单金额。
            $table->string('fee_type', 8)->nullable()->comment('货币种类');
            $table->integer('cash_fee')->nullable()->comment('现金支付金额');
            $table->string('cash_fee_type',16)->nullable()->comment('现金支付货币类型');//货币类型，符合ISO4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
            $table->integer('coupon_fee')->nullable()->comment('总代金券金额');//代金券金额<=订单金额，订单金额-代金券金额=现金支付金额，详见支付金额
            $table->integer('coupon_count')->nullable()->comment('代金券使用数量');
            $table->integer('coupon_type_$n')->nullable()->comment('代金券类型');
            $table->string('coupon_id_$n',20)->nullable()->comment('代金券ID');
            $table->integer('coupon_fee_$n')->nullable()->comment('单个代金券支付金额');
            $table->string('transaction_id',32)->nullable()->comment('微信支付订单号');
            $table->string('out_trade_no',32)->nullable()->comment('商户订单号');
            $table->string('attach',128)->nullable()->comment('商家数据包');
            $table->string('time_end',14)->nullable()->comment('支付完成时间');//20141030133525
            $table->string('return_msg', 128)->nullable()->comment('返回信息');
            $table->timestamps();
            $table->index('transaction_id');
            $table->index('out_trade_no');
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
        Schema::dropIfExists('pay_notifies');
    }
}
