<?php

namespace App\Models;


class Shop extends BaseModel
{

    /**
     * 获取该店铺下卖家：用户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function buyer(){
        return $this->hasMany(Buyer::class,'shop_id','id');
    }
    /**
     * 小程序APP
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wxapps()
    {
        return $this->hasMany(Wxapp::class, 'shop_id', 'id');
    }
    const STATUS_UPPER = 'UPPER';
    const STATUS_LOWER = 'LOWER';
    public static $statusMap = [
        self::STATUS_UPPER => '上架',
        self::STATUS_LOWER => '下架'
    ];
    const TEMPLATE_COMMON = 'common';
    public static $templateMap = [
        self::TEMPLATE_COMMON => '通用模板',
    ];
}
