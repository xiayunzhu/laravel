<?php

namespace App\Models;


class Promo extends BaseModel
{
    public static $fields = ["shop_id","type","title","discount","require_threshold","credit_limit","range","total_count","take_count","used_count","apply_user"
        ,"tickets_available","take_begin","take_end","validity_type","effect_time","invalid_time","days","format","coupon_id","status","explain"];


    ## 优惠券类型  FULL_CUT:满减券；DISCOUNT_COUPON：折扣券
    const PROMO_TYPE_FULL_CUT = 'FULL_CUT';
    const PROMO_TYPE_DISCOUNT_COUPON = 'DISCOUNT_COUPON';
    public static $promoTypeMap = [
        self::PROMO_TYPE_FULL_CUT => '满减券',
        self::PROMO_TYPE_DISCOUNT_COUPON => '折扣券',
    ];

    ## 优惠券状态    ENABLE：启用（未开始）；UNABLE：禁用（已删除）
    const PROMO_STATUS_ENABLE = 'ENABLE';
    const PROMO_STATUS_ONGOING = 'ONGOING';
    const PROMO_STATUS_UNABLE = 'UNABLE';
    public static $promoStatusMap = [
        self::PROMO_STATUS_ENABLE => '启用（未开始）',
        self::PROMO_STATUS_ONGOING => '进行中',
        self::PROMO_STATUS_UNABLE => '禁用',
    ];

    ## 优惠券列表进行状态    PREPARE：未开始；ONGOING：进行中；EXPIRED：已过期
    const PROCEED_STATUS_PREPARE = 'PREPARE';
    const PROCEED_STATUS_ONGOING = 'ONGOING';
    const PROCEED_STATUS_EXPIRED = 'EXPIRED';
    public static $proceedStatusMap = [
        self::PROCEED_STATUS_PREPARE => '未开始',
        self::PROCEED_STATUS_ONGOING => '进行中',
        self::PROCEED_STATUS_EXPIRED => '已过期',
    ];

    ## 使用商品范围类型     ALL_CAN:全部商品可用；PART_CAN：指定商品可用；PART_CANT：指定商品不可用
    const GOOD_RANGE_ALL_CAN = 'ALL_CAN';
    const GOOD_RANGE_PART_CAN = 'PART_CAN';
    const GOOD_RANGE_PART_CANT = 'PART_CANT';
    public static $goodRangeMap = [
        self::GOOD_RANGE_ALL_CAN => '全部商品可用',
        self::GOOD_RANGE_PART_CAN => '指定商品可用',
        self::GOOD_RANGE_PART_CANT => '指定商品不可用',
    ];
    ##使用店铺范围
    const SHOP_RANGE_ALL_CAN = 'ALL_CAN';
    const SHOP_RANGE_PART_CAN = 'PART_CAN';
    const SHOP_RANGE_PART_CANT = 'PART_CANT';
    public static $shopRangeMap = [
        self::SHOP_RANGE_ALL_CAN => '全部店铺可用',
        self::SHOP_RANGE_PART_CAN => '指定店铺可用',
        self::SHOP_RANGE_PART_CANT => '指定店铺不可用',
    ];
    ## 有效期类型    appoint_date：指定日期；appoint_during：指定天数
    const VALIDITY_APPOINT_DATE = 'APPOINT_DATE';
    const VALIDITY_APPOINT_DURING = 'APPOINT_DURING';
    public static $validityMap = [
        self::VALIDITY_APPOINT_DATE => '指定日期',
        self::VALIDITY_APPOINT_DURING => '指定天数',
    ];

    ## 适用用户类型  new：仅限新用户使用；all：全部用户；
    const USER_TYPE_NEW = 'NEW';
    const USER_TYPE_ALL = 'ALL';
    public static $userTypeMap = [
        self::USER_TYPE_NEW => '仅限新用户使用',
        self::USER_TYPE_ALL => '全部用户',
    ];

    ## 使用门槛  not: 无门槛
    const USE_THRESHOLD_NOT = 'NOT';
    public static $useThresholdMap = [
        self::USE_THRESHOLD_NOT => '无门槛'
    ];

    ## 金额上限 not: 无上限
    const AMOUNT_LIMIT_NOT = 'NOT';
    public static $amountLimitMap = [
        self::AMOUNT_LIMIT_NOT => '无上限'
    ];


}
