<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_shops', function (Blueprint $table) {
            $table->increments('id')->comment('营销活动适用店铺表');
            $table->integer('promo_id')->comment('营销活动ID');
            $table->integer('shop_id')->comment('店铺id');

            $table->timestamps();
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
        Schema::dropIfExists('promo_shops');
    }
}
