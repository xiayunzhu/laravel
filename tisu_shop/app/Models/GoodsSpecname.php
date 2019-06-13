<?php

namespace App\Models;

class GoodsSpecname extends BaseModel
{
    //
//    protected $fillable = [];

    const STATUS_YES = 'Y';
    const STATUS_NO = 'N';
    public static $statusMap = [
        self::STATUS_YES => '有效',
        self::STATUS_NO => '无效',
    ];
}
