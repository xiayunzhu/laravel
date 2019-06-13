<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:51
 */

namespace App\Models;


class ShopPromoItem extends BaseModel
{
    const STATUS_ENABLE = 'enable';
    const STATUS_UNABLE = 'unable';
    public static $statusMap = [
        self::STATUS_ENABLE => '启用',
        self::STATUS_UNABLE => '禁用',
    ];
    ##商品选择
    const GOODS_TYPE_MANDATORY = 'MANDATORY';
    const GOODS_TYPE_OPTIONAL = 'OPTIONAL';
    public static $choiceMap = [
        self::GOODS_TYPE_MANDATORY => '必选',
        self::GOODS_TYPE_OPTIONAL => '可选',
    ];
}