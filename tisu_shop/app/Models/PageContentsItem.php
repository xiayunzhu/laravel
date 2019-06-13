<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageContentsItem extends BaseModel
{
    use SoftDeletes;
    public static $fields = ['page_contents_id', 'image_url', 'is_show', 'sort'];

    const SHOW_STATUS_SHOW_OUT = 'hidden';
    const SHOW_STATUS_ON_SHOW = 'display';
    public static $isShowMap = [
        self::SHOW_STATUS_SHOW_OUT => '隐藏',
        self::SHOW_STATUS_ON_SHOW => '显示',
    ];
}
