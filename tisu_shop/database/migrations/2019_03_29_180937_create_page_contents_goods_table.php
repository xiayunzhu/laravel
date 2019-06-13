<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageContentsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_contents_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_contents_id')->comment('卡片ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->timestamps();
            $table->index('page_contents_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_contents_goods');
    }
}
