<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_files', function (Blueprint $table) {
            $table->increments('id')->comment('上传的文件');
            $table->char("folder", 20)->comment('文件对象类型');//存储的目录
            $table->string("object_id", 64)->comment('文件对象ID');
            $table->integer("group_id")->comment('文件分组ID')->default(0);
            $table->string("path", 255)->comment('文件路径');
            $table->string("file_url", 255)->comment('文件路径');
            $table->string("file_name", 255)->comment('文件名称');
            $table->integer("file_size")->default(0)->comment('文件大小');
            $table->string("file_type", 20)->comment('文件类型');
            $table->string("extension", 20)->comment('文件扩展名');
            $table->integer('shop_id')->comment('店铺ID')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->index('object_id','idx_object_id');
            $table->index('shop_id','idx_shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
    }
}
