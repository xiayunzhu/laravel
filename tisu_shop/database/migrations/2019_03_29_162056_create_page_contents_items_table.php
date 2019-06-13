<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageContentsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_contents_items', function (Blueprint $table) {
            $table->increments('id')->comment('卡片内容表');
            $table->integer('page_contents_id')->comment('卡片ID');
            $table->string('image_url',255)->comment('图片地址');
            $table->string('is_show',16)->comment('是否显示')->default(\App\Models\PageContentsItem::SHOW_STATUS_ON_SHOW);// 1：显示； 0：隐藏；
            $table->integer('sort')->comment('排序')->default(100);
            $table->softDeletes();
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
        Schema::dropIfExists('page_contents_items');
    }
}
