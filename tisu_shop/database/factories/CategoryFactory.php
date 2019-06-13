<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'parent_id' => 0,
        'image_url' => $faker->imageUrl(60, 60),
        'introduction' => '测试数据'
    ];
});
