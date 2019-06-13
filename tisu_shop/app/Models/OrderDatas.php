<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDatas extends Model
{
    //

    protected $fillable = ['page_view','turnover_total','turnover_top','order_total','buyer_pay','buyer_order','order_count','order_pay','order_send','time','shop_id'];

    public $timestamps=false;
}
