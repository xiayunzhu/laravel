<?php

namespace App\Http\Requests\Api\Goods;

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
            'goods_id' => 'required',
            'images.*.main' => 'array',
            'images.*.detail' => 'array',
        ];
    }
}
