<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/22
 * Time: 13:35
 */

namespace App\Models;


class Turnover extends BaseModel
{
    const TYPE_PAY = 'pay';
    const TYPE_REFUND = 'refund';
    public static $typeMap = [
        self::TYPE_PAY => '下单',
        self::TYPE_REFUND => '退款',
    ];
}