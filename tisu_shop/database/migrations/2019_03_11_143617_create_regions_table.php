<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id')->comment('区域编码');
            $table->integer('pid')->comment('上级ID');
            $table->string('shortname',128)->comment('简称')->nullable();
            $table->string('name',128)->comment('名称')->nullable();
            $table->string('merger_name',255)->comment('名称')->nullable();
            $table->tinyInteger('level')->comment('级别')->default(0);//0 国家 1 省 2 市 3 区
            $table->string('pinyin',128)->comment('拼音')->default(0);
            $table->string('code',128)->comment('区域编码')->default(0);
            $table->string('zip_code',128)->comment('邮政编码')->default(0);
            $table->string('first',50)->comment('首字母')->default(0);
            $table->string('lng',100)->comment('经度')->default(0);// 经度 Longitude 简写Lng
            $table->string('lat',100)->comment('纬度')->default(0);// 纬度 Latitude 简写Lat
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
        Schema::dropIfExists('regions');
    }
}
