<?php

namespace App\Http\Requests\WeChat\CartItem;

use App\Http\Requests\WeChat\BaseRequest;

class StoreRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'goods_spec_id' => 'required',
            'num' => 'required',
        ];
    }
}
