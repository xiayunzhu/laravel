<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_rules', function (Blueprint $table) {
            $table->increments('id')->comment('运费规则');
            $table->integer('delivery_id')->comment('运费模版ID');
            $table->text('region')->comment('可配送区域');
            $table->double('first')->comment('首重')->default(0);
            $table->decimal('first_fee', 10, 2)->comment('首重费用')->default(0);
            $table->double('additional')->comment('续重')->default(0);
            $table->decimal('additional_fee', 10, 2)->comment('续重费用')->default(0);
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
        Schema::dropIfExists('delivery_rules');
    }
}
