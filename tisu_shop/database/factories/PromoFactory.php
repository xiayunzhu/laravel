<?php
/**
 * Created by PhpStorm.
 * User: ML-05
 * Date: 2019/4/24
 * Time: 15:54
 */

use Faker\Generator as Faker;
use App\Models\Promo;


$factory->define(\App\Models\Promo::class, function (Faker $faker) {

    $title = [
        "清凉夏日节",
        "夏季时装周",
        "万件T恤1元拼",
    ];
    $require_threshold = [200, 50, 30, 'not'];
    $credit_limit = [200, 20, 'not'];
    $take_count = [300, 200];
    $used_count = [0,100,200];



    return [
        "shop_id" => 1,
        "type" => array_rand(Promo::$promoTypeMap),
        "title" => $title[rand(0, 2)],
        "discount" => rand(0, 10),
        "require_threshold" => $require_threshold[rand(0, 3)],
        "credit_limit" => $credit_limit[rand(0, 2)],
        "range" => array_rand(Promo::$goodRangeMap),
        "total_count" => 300,
        "take_count" => $take_count[rand(0, 1)],
        "used_count" => $used_count[rand(0, 2)],
        "apply_user" => array_rand(Promo::$userTypeMap),
        "tickets_available" => rand(1, 10),
        "take_begin" => randomDate('2019-04-24', '2019-05-03'),
        "take_end" => randomDate('2019-04-30', '2019-05-05'),
        "validity_type" => array_rand(Promo::$validityMap),
        "effect_time" => randomDate('2019-04-25', '2019-05-03'),
        "invalid_time" => randomDate('2019-05-01', '2019-05-05'),
        "days" => rand(0, 1),
        "status" => array_rand(Promo::$promoStatusMap),
        "explain" => '不兑换现金、不找零；'
    ];
});


/**
 * 随机生成日期
 * @param $begintime
 * @param string $endtime
 * @param bool $now
 * @return false|int|string
 */
function randomDate($begintime, $endtime = "", $now = true)
{
    $begin = strtotime($begintime);
    $end = $endtime == "" ? mktime() : strtotime($endtime);
    $timestamp = rand($begin, $end);
    $time = $now ? date("Y-m-d H:i:s", $timestamp) : $timestamp;
    return strtotime($time);
}