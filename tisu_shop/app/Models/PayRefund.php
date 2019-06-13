<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayRefund extends Model
{
    //
    protected $fillable = ['return_code', 'err_code', 'err_code_des', 'appid', 'mch_id', 'nonce_str', 'sign', 'transaction_id', 'out_trade_no', 'out_refund_no', 'refund_id', 'refund_fee', 'settlement_refund_fee', 'total_fee', 'settlement_total_fee', 'fee_type', 'cash_fee', 'cash_fee_type', 'cash_refund_fee', 'coupon_type_$n', 'coupon_refund_fee', 'coupon_refund_fee_$n', 'coupon_refund_count', 'coupon_refund_id_$n', 'return_msg'];
}
