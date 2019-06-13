<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['shop_id', 'type', 'status', 'content', 'details'];

    // 消息状态
    const STATUS_WAIT = 0;
    const STATUS_DONE = 1;

    public static $statusMap = [
        self::STATUS_WAIT => '未读',
        self::STATUS_DONE => '已读'
    ];

    // 消息类型
    const TYPE_ORDER = 'ORDER'; //付款订单
    const TYPE_REFUND = 'REFUND';   //退货订单
    const TYPE_SYSTEM = 'SYSTEM';   //系统消息
    const PUBLISH_STATUS_UPPER = 'UPPER';   //商品上架通知
    const PUBLISH_STATUS_LOWER = 'LOWER';   //商品下架通知
    const EVENT_STATUS_ENABLE = 'EVENT_STATUS_ENABLE';   //活动报名成功

    public static $typeMap = [
        self::TYPE_ORDER => '订单消息',
        self::TYPE_REFUND => '退款消息',
        self::TYPE_SYSTEM => '后台消息',
        self::PUBLISH_STATUS_UPPER => '上架',
        self::PUBLISH_STATUS_LOWER => '下架',
        self::EVENT_STATUS_ENABLE => '活动报名成功',
    ];


}
