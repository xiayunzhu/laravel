<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_parameters', function (Blueprint $table) {
            $table->increments('id')->comment('商品参数表');

            $table->integer('org_goods_id')->comment('运营商品ID');
            $table->string('parameter_name', 255)->comment('参数名称');
            $table->string('parameter_value', 255)->comment('参数值');
            $table->string('status', 16)->comment('状态');

            $table->timestamps();
            $table->index('org_goods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_parameters');
    }
}
