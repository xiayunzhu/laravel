<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgGoodImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_good_images', function (Blueprint $table) {
            $table->increments('id')->comment('原始商品图片表');
            $table->integer('org_goods_id')->comment('商品ID');
            $table->integer('image_id')->comment('商品图片');
            $table->string("file_url", 255)->comment('文件路径');
            $table->string('property', 16)->comment('属性:主图,列表图,详情图')->default('');
            $table->integer('sort')->comment('排序')->default(100);
            $table->integer('create_time')->comment('创建时间');
            $table->string('is_show',16)->comment('是否显示')->default(\App\Models\GoodsImage::SHOW_STATUS_ON_SHOW);
            $table->timestamps();
            $table->index('org_goods_id','idx_org_images_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_good_images');
    }
}
