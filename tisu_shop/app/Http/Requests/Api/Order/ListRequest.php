<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\BaseRequest;
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
            'shop_id' => 'required',
            'page' => 'numeric',
            'per_page' => 'numeric',
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
            'shop_id.required' => ':attribute 店铺ID 必传',
            'order_status.in' => ':attribute 必须是' . implode('|', array_keys(Order::$orderStatusMap)) . '(' . implode('|', array_values(Order::$orderStatusMap)) . ')',

        ];
    }
}
