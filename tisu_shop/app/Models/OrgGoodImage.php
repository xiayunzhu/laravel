<?php

namespace App\Models;


class OrgGoodImage extends BaseModel
{
    //图片属性-归属哪个模块
    const PROPERTY_MAIN = 'main';
    const PROPERTY_LOGO = 'logo';
    const PROPERTY_DETAIL = 'detail';
    public static $propertyMap = [
        self::PROPERTY_MAIN => '首图',
        self::PROPERTY_LOGO => '列表图',
        self::PROPERTY_DETAIL => '详情',
    ];
    public static $fields = ['org_goods_id', 'image_id', 'file_url', 'property', 'sort', 'create_time'];

}
