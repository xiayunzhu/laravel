<?php

namespace App\Models;

class OrderAddress extends BaseModel
{
    //
    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['order_no', 'receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'zip_code', 'buyer_id', 'create_time', 'shop_id'];
}
