<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teasings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title','50')->comment('标题');
            $table->integer('user_id')->comment('用户ID');
            $table->text('content')->comment('吐槽内容');
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
        Schema::dropIfExists('teasings');
    }
}
