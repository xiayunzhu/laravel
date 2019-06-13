<?php

namespace App\Models;


class RefundAddresses extends BaseModel
{
    public static $fields = ['refund_no', 'receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'zip_code', 'buyer_id', 'shop_id'];

}
