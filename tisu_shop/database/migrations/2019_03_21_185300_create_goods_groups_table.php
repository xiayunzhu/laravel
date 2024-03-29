<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('分组名称');
            $table->integer('shop_id')->comment('归属店铺');
            $table->softDeletes();
            $table->timestamps();
            $table->index('shop_id','idx_goods_group_shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_groups');
    }
}
