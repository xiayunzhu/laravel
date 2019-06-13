<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


class RefundLogistics extends BaseModel
{
    use SoftDeletes;

    public static $fields = ['refund_id', 'logistics_no', 'logistics_name'];


}
