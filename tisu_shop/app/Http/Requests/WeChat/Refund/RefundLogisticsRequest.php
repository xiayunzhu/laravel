<?php

namespace App\Http\Requests\WeChat\Refund;

use App\Http\Requests\WeChat\BaseRequest;
class RefundLogisticsRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'refund_id' => 'required',
            'logistics_no' => 'required',
            'logistics_name' => 'required'
        ];
    }

}
