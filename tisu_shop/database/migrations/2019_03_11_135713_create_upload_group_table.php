<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_type', 10)->comment('分组类型');
            $table->string('group_name', 30)->comment('分组名称');
            $table->integer('sort')->comment('分组名称');
            $table->integer('shop_id')->comment('店铺ID')->default(0);
            $table->timestamps();
            $table->index('shop_id','idx_group_shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_groups');
    }
}
