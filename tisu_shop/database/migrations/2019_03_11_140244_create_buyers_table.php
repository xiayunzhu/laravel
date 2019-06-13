<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->increments('id')->comment('店铺的客户信息');
            $table->string('open_id', 128)->comment('用户小程序的open_id')->default('');
            $table->string('phone', 20)->comment('手机号')->default('');
            $table->string('union_id')->comment('微信小程序的 unionId')->nullable()->default('');
            $table->string('nick_name', 255)->comment('微信昵称')->default('');
            $table->string('avatar_url', 255)->comment('头像链接')->default('');
            $table->tinyInteger('gender')->comment('性别')->default(0);//0未知 1 男 2 女
            $table->string('remark', 128)->comment('备注')->default('');
            $table->string('source', 64)->comment('来源')->default('');
            $table->string('language', 32)->comment('语言')->default('');
            $table->string('country', 50)->comment('国家')->default('');
            $table->string('province', 50)->comment('省')->default('');
            $table->string('city', 50)->comment('市')->default('');
            $table->integer('address_id')->comment('地址ID')->default(0);
            $table->integer('shop_id')->comment('店铺ID');
            $table->string('appid', 64)->comment('app_id')->default('');
            $table->string('has_buy', 8)->comment('是否购买过')->default(\App\Models\Buyer::HAS_BUY_NO);
            $table->timestamps();

            $table->index('open_id');
            $table->index('has_buy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyers');
    }
}
