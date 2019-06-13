<?php

namespace App\Http\Requests\Api\GoodsGroupItem;

use App\Http\Requests\Api\BaseRequest;

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
            'goods_group_id' => 'required',
            'goods_ids' => 'required|array',
            'shop_id' => 'required',
        ];
    }
}
