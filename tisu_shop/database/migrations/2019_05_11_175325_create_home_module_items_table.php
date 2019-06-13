<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeModuleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_module_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->comment('模块ID');

            $table->string('detail')->comment('详情参数-商品ID/专题页ID');
            $table->string('detail_type', 16)->comment('详情类型');

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
        Schema::dropIfExists('home_module_items');
    }
}
