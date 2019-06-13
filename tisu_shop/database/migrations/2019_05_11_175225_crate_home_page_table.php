<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateHomePageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page', function (Blueprint $table) {
            $table->increments('id')->comment('首页id');
            $table->string('title',255)->comment('标题')->default('');
            $table->string('image_url',255)->comment('图片地址')->default('');
            $table->enum('template', ['三个以上的商品', '三个商品','两个商品'])->comment('模板类型');
            $table->string('intro',255)->comment('简介')->default('');
            $table->integer('page_type')->comment('链接页面类型');    //1 专题   2 商品
            $table->integer('display_module')->comment('显示模块')->nullable();    //1 今日主推   2 菜单栏  3新品速递
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
        Schema::dropIfExists('home_page');
    }
}
