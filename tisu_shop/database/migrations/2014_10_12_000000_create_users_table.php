<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ## 修改字段:确保将 doctrine/dbal 依赖添加到 composer.json
        # https://laravel-china.org/docs/laravel/5.5/migrations/1329#3a73db
        # composer require doctrine/dbal
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone', 20)->comment('手机')->nullable();
            $table->string('email', 64)->comment('用户登录账号')->default('');
            $table->string('username', 64)->comment('后台用户登录账号')->default('');
            $table->string('open_id', 128)->comment('wx用户唯一标识')->default('');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->tinyInteger('sex')->comment('性别:0-女 1-男')->default(0);
            $table->integer('login_at')->comment('上次登录时间')->nullable()->default(0);
            $table->string('login_ip', 64)->comment('上次登录IP')->nullable()->default('');
            $table->string('avatar')->comment('头像链接或者存储路径')->nullable();
            $table->tinyInteger('bool_admin')->comment('是否超级管理员，1:是，0:否')->default(0);
            $table->tinyInteger('status')->comment('状态，2:已停用 1:启用，0:未激活')->default(0);
            $table->string('user_type', 16)->comment('用户类型')->nullable()->default(\App\Models\User::USER_TYPE_ADMIN);
            $table->string('qq_code', 64)->comment('QQ号码')->nullable()->default('');
            $table->string('wx_code', 64)->comment('微信号')->nullable()->default('');
            $table->string('img_url')->comment('头像')->nullable()->default('');
            $table->timestamps();
            $table->index('email');
            $table->index('bool_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
