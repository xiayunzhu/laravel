<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_events', function (Blueprint $table) {
            $table->increments('id')->comment('店铺报名的活动');
            $table->integer('shop_id')->comment('店铺id');
            $table->integer('event_id')->comment('活动ID');
            $table->string('status', 16)->comment('审核状态');

            $table->timestamps();
            $table->index('status');
            $table->index('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_events');
    }
}
