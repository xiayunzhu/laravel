<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->increments('id')->comment('卡片表');
            $table->integer('shop_id')->comment('店铺ID');
            $table->string('image_url',255)->comment('封面图片地址');
            $table->string("title",100)->comment('标题');
            $table->string('describe')->comment("描述")->default('');
            $table->string("type",16)->default(\App\Models\PageContent::TYPE_STYLE_ONE)->comment("样式类型");
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
        Schema::dropIfExists('page_contents');
    }
}
