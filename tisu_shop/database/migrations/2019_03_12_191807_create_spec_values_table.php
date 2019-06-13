<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spec_values', function (Blueprint $table) {
            $table->increments('id')->comment('规格属性值');
            $table->string('spec_value', 255)->comment('值');
            $table->integer('spec_id')->comment('规格属性ID');
            $table->timestamps();
            $table->index('spec_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spec_value');
    }
}
