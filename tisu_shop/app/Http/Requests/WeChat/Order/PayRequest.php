<?php

namespace App\Http\Requests\WeChat\Order;

use App\Http\Requests\WeChat\BaseRequest;

class PayRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_no' => 'required',
        ];
    }
}
