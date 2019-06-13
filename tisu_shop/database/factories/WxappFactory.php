<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Wxapp::class, function (Faker $faker) {
    return [
        'app_name' => $faker->name . ' 小程序商城',
        'app_id' => 'wx704aa63adc4ef822_' . rand(0, 100),
        'app_secret' => $faker->md5,
        'is_service' => 1,
        'shop_id' => 10001
    ];
});
