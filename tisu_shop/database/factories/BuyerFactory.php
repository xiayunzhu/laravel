<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Buyer::class, function (Faker $faker) {
    return [
        "open_id" => "obIYo4xOlxl1Csk2VSUTddEC5g".rand(10,99),
        "phone" => rand(1000000000000,199999999999),
        "union_id" => '',
        "nick_name" => $faker->name,
        "avatar_url" => "https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKJtt4z2HqcRQlzUXZlvJ7xVnrZv668JWUib5EeZr96KZqLVALxgyADmo2ZWUicFPZoMchjM41P8rMQ/132",
        "gender" => 1,
        "remark" => "测试",
        "source" => "wechat",
        "language" => "zh",
        "country" => "中国",
        "province" => "浙江",
        "city" => "杭州",
        "address_id" => 1,
        "shop_id" => 10001,
    ];
});
