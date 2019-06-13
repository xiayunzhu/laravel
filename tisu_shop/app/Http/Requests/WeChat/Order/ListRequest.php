<?php

namespace App\Http\Requests\WeChat\Order;

use App\Http\Requests\WeChat\BaseRequest;
use App\Models\Order;
use Illuminate\Validation\Rule;

class ListRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'buyer_id' => 'required',
            'order_status' => Rule::in(array_keys(Order::$orderStatusMap)),
            'refund_status' => Rule::in(array_keys(Order::$refundStatusMap)),
            'recent' => Rule::in([0, 1])
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'buyer_id.required' => ':attribute 买家ID 必传',
            'order_status.in' => ':attribute 必须是' . implode('|', array_keys(Order::$orderStatusMap)) . '(' . implode('|', array_values(Order::$orderStatusMap)) . ')',
            'refund_status.in' => ':attribute 必须是' . implode('|', array_keys(Order::$refundStatusMap)) . '(' . implode('|', array_values(Order::$refundStatusMap)) . ')',
            'recent.in' => ':attribute 必须是 1或者0 (1:近期,0:不限)'

        ];
    }
}
