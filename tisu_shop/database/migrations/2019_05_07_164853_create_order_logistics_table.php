<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_logistics', function (Blueprint $table) {
            $table->increments('id')->comment('发货信息表');
            $table->string('order_no', 32)->comment('主订单编号');
            $table->string('item_no', 32)->comment('子订单编号');
            $table->integer('shop_id')->comment('店铺ID')->default(0);
            $table->string('logistics_company')->comment('物流公司')->nullable()->default('');
            $table->string('courier_number')->comment('快递单号')->nullable()->default('');
            $table->string('status', 16)->comment('状态')->default(0);
            $table->timestamps();

            $table->index('order_no');
            $table->index('item_no');
            $table->index('shop_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_logistics');
    }
}
