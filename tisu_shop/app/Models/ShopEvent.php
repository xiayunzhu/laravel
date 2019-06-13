<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:49
 */

namespace App\Models;


class ShopEvent extends BaseModel
{
    const STATUS_ENABLE = 'enable';
    const STATUS_UNABLE = 'unable';
    public static $statusMap = [
        self::STATUS_ENABLE => '启用',
        self::STATUS_UNABLE => '禁用',
    ];
}