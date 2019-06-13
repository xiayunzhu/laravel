<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxapps', function (Blueprint $table) {
            $table->increments('id')->comment('微信小程序信息');
            $table->string('app_name', 64)->comment('小程序名称');
            $table->string('app_id', 64)->comment('小程序ID');//
            $table->string('app_secret', 64)->comment('小程序密钥');
            $table->tinyInteger('is_service')->comment('是否服务');
            $table->integer('service_image_id')->comment('服务图片')->default(0);
            $table->tinyInteger('is_phone')->comment('手机用户')->default(0);
            $table->string('phone_no', 20)->comment('手机号')->default(0);
            $table->integer('phone_image_id')->comment('手机图片')->default(0);
            $table->string('mchid', 64)->comment('微信支付商户号')->nullable();//
            $table->string('apikey', 64)->comment('微信支付密钥')->nullable();
            $table->integer('shop_id')->comment('店铺');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('app_id','idx_wxapp_app_id');
            $table->index('shop_id','idx_wxapp_shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wxapps');
    }
}
