<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id')->comment('卖家店铺表');
            $table->integer('shop_code')->comment('店铺代号');//自动生成 10000 + id
            $table->string("shop_nick", 32)->comment('店铺昵称');
            $table->string('shop_name', 32)->comment('店铺名称');
            $table->string('icon_url')->comment('店铺图标')->default('');
            $table->string('introduction')->comment('店铺简介')->default('');
            $table->string('user_id')->comment('归属的用户ID');//卖家ID-红人
            $table->string('template')->comment('店铺模板', 32)->default(\App\Models\Shop::TEMPLATE_COMMON);
            $table->string('status', 32)->comment('状态')->default(\App\Models\Shop::STATUS_UPPER);
            $table->string('qr_url')->comment('店铺二维码', 255)->default('');
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
        Schema::dropIfExists('shops');
    }
}
