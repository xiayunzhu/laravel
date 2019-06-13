<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodsLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_goods_labels', function (Blueprint $table) {
            $table->increments('id')->comment('原商品标签');
            $table->integer('org_goods_id')->comment('org商品ID');
            $table->string('label_value', 32)->comment('标签');
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
        Schema::dropIfExists('org_goods_labels');
    }
}
