<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_modules', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type', 16)->comment('模块类型');

            $table->string('title', 128)->comment('标题');
            $table->string('image_url')->comment('图片链接')->nullable();
            $table->string('description')->comment('简介')->nullable();

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
        Schema::dropIfExists('home_modules');
    }
}
