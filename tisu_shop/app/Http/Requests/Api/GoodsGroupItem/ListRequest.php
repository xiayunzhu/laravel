<?php

namespace App\Http\Requests\Api\GoodsGroupItem;

use App\Http\Requests\Api\BaseRequest;

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
            'goods_group_id' => 'nullable',
        ];
    }
}
