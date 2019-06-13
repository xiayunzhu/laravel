<?php

namespace App\Http\Requests\Api;

class BuyerAddressRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'numeric',
            'per_page' => 'numeric',
            'buyer_id' => 'required',//中间件自动添加
            'shop_id' => 'required',//中间件自动添加

        ];
    }


}
