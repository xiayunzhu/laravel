<?php

namespace App\Models;


class PageContent extends BaseModel
{
    public static $fields = ['shop_id', 'image_url', 'title', 'describe', 'goods_ids', 'type'];

    const TYPE_STYLE_ONE = 'styleOne';
    const TYPE_STYLE_TWO = 'styleTwo';
    const TYPE_STYLE_THREE = 'styleThree';
    const TYPE_STYLE_FOUR = 'styleFour';
    public static $pageContentTypeMap = [
        self::TYPE_STYLE_ONE => '样式一',
        self::TYPE_STYLE_TWO => '样式二',
        self::TYPE_STYLE_THREE => '样式三',
        self::TYPE_STYLE_FOUR => '样式四',
    ];
}
