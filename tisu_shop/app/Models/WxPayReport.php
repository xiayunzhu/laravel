<?php

namespace App\Models;

class WxPayReport extends BaseModel
{
    //
    protected $fillable = ['appid', 'code_url', 'device_info', 'err_code', 'err_code_des', 'interface_url', 'mch_id', 'nonce_str', 'prepay_id', 'result_code', 'return_msg', 'sign', 'trade_type', 'out_trade_no', 'create_time', 'total_fee'];
}
