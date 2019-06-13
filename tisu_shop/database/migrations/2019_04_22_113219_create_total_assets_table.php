<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("assets", 10, 2)->comment('总收入');
            $table->integer('shop_id')->comment('店铺ID');
            $table->decimal("receivable_money", 10, 2)->comment('可提现金额')->nullable();
            $table->decimal("money_received", 10, 2)->comment('已提现金额')->nullable();
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
        Schema::dropIfExists('total_assets');
    }
}
