<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_datas', function (Blueprint $table) {
            $table->increments('id')->comment('每日客户数据-按天存');
            $table->integer('shop_id')->comment('店铺ID');
            $table->integer('customer_total')->comment('客户总数')->nullable();
            $table->integer('customer_day')->comment('当天客户总数');
            $table->integer('customer_new')->comment('新增客户数');
            $table->integer('time')->comment('日期');

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
        Schema::dropIfExists('customer_datas');
    }
}
