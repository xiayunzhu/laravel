<?php

namespace App\Http\Requests\WeChat\Goods;

use App\Http\Requests\WeChat\BaseRequest;

class DetailRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'goods_id' => 'required'
        ];
    }
}
