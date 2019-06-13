<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_addresses', function (Blueprint $table) {
            $table->increments('id')->comment('客户收货地址');
            $table->string('receiver', 32)->comment('收件人');
            $table->string('mobile', 20)->comment('手机');
            $table->string('phone', 20)->comment('座机')->nullable()->default('');
            $table->string('province', 32)->comment('省')->default('');
            $table->string('city', 32)->comment('市')->default('');
            $table->string('district', 32)->comment('区')->default('');
            $table->string('detail', 255)->comment('详细地址')->default('');
            $table->string('zip_code', 32)->comment('邮政编码')->nullable()->default('');
            $table->tinyInteger('is_default')->comment('默认地址')->default(0);//1：默认地址 0:否
            $table->integer('buyer_id')->comment('买家ID')->default(0);
            $table->integer('user_id')->comment('用户表的ID')->default(0);
            $table->integer('shop_id')->comment('店铺ID');
            $table->softDeletes();
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
        Schema::dropIfExists('buyer_addresses');
    }
}
