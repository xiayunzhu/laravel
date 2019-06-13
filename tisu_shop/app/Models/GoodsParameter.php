<?php

namespace App\Models;


class GoodsParameter extends BaseModel
{
    public static $fields = [ 'org_goods_id', 'parameter_name', 'parameter_value', 'status'];
    // 是否显示
    const STATUS_SHOW_OUT = 'N';
    const STATUS_ON_SHOW = 'Y';
    public static $isStatusMap = [
        self::STATUS_SHOW_OUT => '隐藏',
        self::STATUS_ON_SHOW => '显示',
    ];
}