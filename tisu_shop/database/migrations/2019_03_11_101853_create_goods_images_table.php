<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_images', function (Blueprint $table) {
            $table->increments('id')->comment('商品图片');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('image_id')->comment('商品图片');
            $table->string("file_url", 255)->comment('文件路径');
            $table->string('property', 16)->comment('属性:主图,列表图,详情图')->default('');
            $table->integer('sort')->comment('排序')->default(100);
            $table->integer('shop_id')->comment('店铺ID');
            $table->integer('create_time')->comment('创建时间');
            $table->timestamps();
            $table->softDeletes();
            $table->index('goods_id', 'idx_image_goods_id');
            $table->string('is_show',16)->comment('是否显示')->default(\App\Models\GoodsImage::SHOW_STATUS_ON_SHOW);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_images');
    }
}
