<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('refund_id')->comment('退款订单ID');
            $table->string('logistics_no', 64)->comment('快递单号');
            $table->string('logistics_name', 128)->comment('快递公司名称');
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
        Schema::dropIfExists('refund_logistics');
    }
}
