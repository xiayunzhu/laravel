<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_goods_logs', function (Blueprint $table) {
            $table->increments('id')->comment('商品操作日志');
            $table->integer('org_goods_id')->comment('原商品ID');
            $table->text('description')->comment('内容描述');
            $table->string('create_op')->comment('操作人')->nullable()->default('');
            $table->integer('create_op_id')->comment('操作人ID')->nullable()->default(0);
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
        Schema::dropIfExists('org_goods_logs');
    }
}
