<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsImage extends BaseModel
{
    use SoftDeletes;
    //
    //图片属性-归属哪个模块
    const PROPERTY_MAIN = 'main';
    const PROPERTY_LOGO = 'logo';
    const PROPERTY_DETAIL = 'detail';
    public static $propertyMap = [
        self::PROPERTY_MAIN => '首图',
        self::PROPERTY_LOGO => '列表图',
        self::PROPERTY_DETAIL => '详情',
    ];

    // 是否显示
    const SHOW_STATUS_SHOW_OUT = 'hidden';
    const SHOW_STATUS_ON_SHOW = 'display';
    public static $isShowMap = [
        self::SHOW_STATUS_SHOW_OUT => '隐藏',
        self::SHOW_STATUS_ON_SHOW => '显示',
    ];

    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['goods_id', 'image_id', 'file_url', 'property', 'sort', 'shop_id', 'create_time'];

}
