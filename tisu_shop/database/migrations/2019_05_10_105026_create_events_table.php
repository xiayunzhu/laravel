<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id')->comment('活动表');
            $table->string('title')->comment('活动名称');
            $table->string('image_url')->comment('图片地址')->default('');

            $table->integer('event_begin')->comment('活动开始时间')->nullable();
            $table->integer('event_end')->comment('活动结束时间')->nullable();

            $table->string('type', 128)->comment('活动类型');

            $table->string('explain')->comment('说明')->nullable();
            $table->string('status', 16)->comment('状态');

            $table->timestamps();
            $table->index('status');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
