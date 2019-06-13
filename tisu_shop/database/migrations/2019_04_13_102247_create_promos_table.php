<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 32)->comment('营销类型');
            $table->string('title')->comment('活动名称');
            $table->string('promo_tag')->comment('活动标签')->nullable();

            $table->integer('discount')->comment('面值-优惠金额-折扣or金额')->nullable();

            $table->integer('require_threshold')->comment('活动门槛')->nullable()->default(0);
            $table->integer('credit_limit')->comment('优惠额度上限')->nullable();

            $table->string('range', 16)->comment('适用商品');
            $table->integer('total_count')->comment('单店发放总量')->nullable();
            $table->integer('take_count')->comment('已领总量')->default(0);

            $table->string('apply_user', 32)->comment('限购类型')->nullable();
            $table->integer('tickets_available')->comment('每人可领取张数-限购数量')->nullable()->default(1);

            $table->string('is_preheat', 16)->comment('是否预热')->nullable();
            $table->integer('preheat_begin')->comment('预热开始时间')->nullable();
            $table->integer('preheat_end')->comment('预热结束时间')->nullable();

            $table->string('validity_type', 32)->comment('有效期类型');
            $table->integer('effect_time')->comment('生效时间')->nullable();
            $table->integer('invalid_time')->comment('失效时间')->nullable();
            $table->integer('days')->comment('有效天数')->nullable()->default(0);

            $table->string('apply_shop', 16)->comment('适用店铺');
            $table->string('market_cost', 32)->comment('营销成本');
            $table->string('deliver_way', 32)->comment('派送类型')->nullable();
            $table->string('original_cost', 16)->comment('是否仅原价购买商品时可用')->nullable();

            $table->string('status', 16)->comment('优惠券状态');
            $table->string('explain')->comment('说明')->nullable();
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
        Schema::dropIfExists('promos');
    }
}
