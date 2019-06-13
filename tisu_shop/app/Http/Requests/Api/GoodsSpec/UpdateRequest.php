<?php

namespace App\Http\Requests\Api\GoodsSpec;

use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'goods_price' => 'numeric',
            'line_price' => 'numeric',
            'virtual_quantity' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'goods_price.integer'=>'标价 :attribute 必须是数字',
            'line_price.integer'=>'划线价 :attribute 必须是数字',
            'virtual_quantity.integer'=>'虚拟库存 :attribute 必须是个整数',
        ];
    }
}
