<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxPayReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_pay_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('interface_url', 191)->nullable()->comment('请求链接');
            $table->string('appid', 32)->nullable()->comment('小程序ID');;
            $table->string('mch_id', 32)->nullable()->comment('商户号');
            $table->string('device_info', 32)->nullable()->comment('设备号	');
            $table->string('nonce_str', 32)->nullable()->comment('随机字符串');
            $table->string('sign', 32)->nullable()->comment('签名');
            $table->string('result_code', 16)->nullable()->comment('业务结果');
            $table->string('err_code', 32)->nullable()->comment('错误代码');
            $table->string('err_code_des', 128)->nullable()->comment('错误代码描述	');
            $table->string('return_msg', 128)->nullable()->comment('返回信息');
            $table->string('trade_type', 16)->nullable()->comment('交易类型	');
            $table->string('prepay_id', 64)->nullable()->comment('预支付交易会话标识');
            $table->string('code_url', 64)->nullable()->comment('二维码链接');
            $table->string('out_trade_no', 32)->nullable()->comment('商户订单号');
            $table->integer('create_time')->comment('实际创建时间')->default(0);
            $table->integer('total_fee')->comment('金额')->default(0);
            $table->timestamps();
            $table->index('appid');
            $table->index('prepay_id');
            $table->index('out_trade_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wx_pay_reports');
    }
}
