<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFileUsedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_file_used', function (Blueprint $table) {
            $table->increments('id')->comment('已使用的文件记录');
            $table->integer('file_id')->comment('文件ID');
            $table->integer('from_id')->comment('来源');
            $table->string('from_type', 20)->comment('来源类型');
            $table->integer('shop_id')->comment('店铺ID')->default(0);
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
        Schema::dropIfExists('upload_file_used');
    }
}
