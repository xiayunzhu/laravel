<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Shop::class, function (Faker $faker) {
    return [
        'shop_code' => rand(10000, 99999),
        'shop_nick' => $faker->name,
        'shop_name' => $faker->name,
        'icon_url' => $faker->imageUrl(68, 68),
        'introduction' => '测试店铺',
        'user_id' => 3
    ];
});
