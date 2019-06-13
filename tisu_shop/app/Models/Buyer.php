<?php

namespace App\Models;


class Buyer extends BaseModel
{
    ## 性别 0女 1男
    const GENDER_MEN = 1;
    const GENDER_WOMEN = 0;
    public static $genderMap = [
        self::GENDER_MEN => '男',
        self::GENDER_WOMEN => '女',
    ];

    /**
     * 买家(客户)收藏
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorites::class, 'buyer_id', 'id');
    }

    /**
     * 客户订单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id', 'id');

    }

    /**
     * 归属的店铺
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'open_id', 'open_id');
    }

    ## 字段枚举
    const HAS_BUY_YES = 'Y';
    const HAS_BUY_NO = 'N';
    public static $hasBuyMap = [
        self::HAS_BUY_YES => '有购买记录',
        self::HAS_BUY_NO => '无购买记录',
    ];
}
