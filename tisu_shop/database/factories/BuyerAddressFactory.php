<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\BuyerAddress::class, function (Faker $faker) {
    return [
        'receiver' => $faker->name,
        'mobile' => '1' . rand(10, 99) . rand(100, 999) . rand(100, 999) . rand(10, 99),
        'phone' => '1' . rand(10, 99) . rand(100, 999) . rand(100, 999) . rand(10, 99),
        'province' => '浙江省',
        'city' => '杭州市',
        'district' => '江干区',
        'detail' => '东谷创业园',
        'zip_code' => '330327',
        'is_default' => 0,
        'buyer_id' => 0,
        'shop_id' => 0,
    ];
});
