<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->comment('商品分类');
            $table->string('code', 32)->comment('分类代号')->nullable();
            $table->string('name', 64)->comment('分类名称');
            $table->integer('parent_id')->comment('父类ID')->default(0);
            $table->string('image_url', 255)->comment('图片链接')->nullable();
            $table->integer('sort')->comment('排序')->default(0);
            $table->string('introduction')->comment('简介')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('parent_id', 'index_parent_id');
        });
    }

    /**delivery
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
