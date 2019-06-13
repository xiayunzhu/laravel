<?php

namespace App\Http\Requests\WeChat\Goods;

use App\Http\Requests\WeChat\BaseRequest;

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
            'shop_id' => 'required'
        ];
    }
}
