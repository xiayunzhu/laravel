<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Goods::class, function (Faker $faker) {
    return [
        "category_id" => 1,
        "deduct_stock_type" => "1",
        "delivery_id" => "1",
        "goods_sort" => 100,
        "introduction" => $faker->name,
        "name" => $faker->name,
        "publish_status" => "upper",
        "sales_actual" => rand(100, 999),
        "sales_initial" => rand(100, 999),
        "sales_status" => "on_sale",
        "shop_id" => 1,
        "spec_type" => 0,
        "title" => $faker->name,
        "version" => 1
    ];
});
