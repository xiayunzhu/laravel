<?php

namespace App\Models;


class PromoItem extends BaseModel
{
    public static $fields = ['shop_id','promo_id','goods_id','status'];


    ## 状态    enable：启用；unable：禁用
    const STATUS_ENABLE = 'enable';
    const STATUS_UNABLE = 'unable';
    public static $statusMap = [
        self::STATUS_ENABLE => '启用',
        self::STATUS_UNABLE => '禁用',
    ];
    ##商品选择
    const GOODS_TYPE_MANDATORY = 'MANDATORY';
    const GOODS_TYPE_OPTIONAL = 'OPTIONAL';
    const GOODS_TYPE_COMMONLY = 'COMMONLY';
    public static $choiceMap = [
        self::GOODS_TYPE_MANDATORY => '必选',
        self::GOODS_TYPE_OPTIONAL => '可选',
        self::GOODS_TYPE_COMMONLY => '一般'
    ];
}
