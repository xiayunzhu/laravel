<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\BaseRequest;


class OrderPriceRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required',
            'total_fee' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'id.required'=>'订单id 必传',
            'total_fee.required' => '订单价格不能为空',
            'total_fee.numeric' => '订单价格为整数',
        ];
    }
}
