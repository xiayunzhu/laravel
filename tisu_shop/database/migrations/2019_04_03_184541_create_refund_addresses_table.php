<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_addresses', function (Blueprint $table) {
            $table->increments('id')->comment('退款地址表');
            $table->string('refund_no', 32)->comment('主订单编号');
            $table->string('receiver', 32)->comment('收件人')->default('');
            $table->string('mobile', 20)->comment('手机');
            $table->string('phone', 20)->comment('座机')->default('');
            $table->string('province', 32)->comment('省')->default('');
            $table->string('city', 32)->comment('市')->default('');
            $table->string('district', 32)->comment('区')->default('');
            $table->string('detail', 255)->comment('详细地址')->default('');
            $table->string('zip_code', 32)->comment('邮政编码')->nullable()->default('');
            $table->integer('buyer_id')->comment('买家id')->default(0);
            $table->integer('shop_id')->comment('店铺ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_addresses');
    }
}
