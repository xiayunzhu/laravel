<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialPageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_page_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->comment('专题页ID');
            $table->integer('goods_id')->comment('商品ID');

            $table->string('status', 16)->comment('状态');

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
        Schema::dropIfExists('special_page_items');
    }
}
