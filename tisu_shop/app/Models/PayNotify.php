<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayNotify extends Model
{
    //
    protected $fillable = ['appid', 'attach', 'bank_type', 'cash_fee', 'cash_fee_type', 'coupon_count', 'coupon_fee', 'coupon_fee_$n', 'coupon_id_$n', 'coupon_type_$n', 'device_info', 'err_code', 'err_code_des', 'fee_type', 'is_subscribe', 'mch_id', 'nonce_str', 'openid', 'out_trade_no', 'result_code', 'return_msg', 'settlement_total_fee', 'sign', 'time_end', 'total_fee', 'trade_type', 'transaction_id'];
}
