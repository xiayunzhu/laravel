<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\OrgGood::class, function (Faker $faker) {
    return [
        'name' =>'Dr.Morita森田玻尿酸复合面膜 99RMB/10片' ,
        'title' =>'Dr.Morita森田玻尿酸复合面膜 99RMB/10片',
        'item_code'=>0,
        'brand_id'=>2,
        'category_id'=>1,
        'spec_type'=>1,
        'deduct_stock_type'=>1,
        'content'=>'我滴妈',
        'introduction'=>'商品简介',
        'sales_initial'=>1,
        'sales_actual'=>1,
        'goods_sort'=>1,
        'delivery_id'=>1,
        'sales_status'=>'sold_out',
        'publish_status'=>'upper',
        'version'=>1,
        'commission_rate'=>0.00
    ];
});
