<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:49
 */

namespace App\Models;


class Event extends BaseModel
{
    public static $query_fileds = ['type'];
    const EVENT_STATUS_ENABLE = 'ENABLE';
    const EVENT_STATUS_ONGOING = 'ONGOING';
    const EVENT_STATUS_UNABLE = 'UNABLE';
    public static $promoStatusMap = [
        self::EVENT_STATUS_ENABLE => '启用（未开始）',
        self::EVENT_STATUS_ONGOING => '进行中',
        self::EVENT_STATUS_UNABLE => '禁用',
    ];

}