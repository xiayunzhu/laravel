<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Goods;
use Illuminate\Validation\Rule;

class GoodStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'org_goods_id' => 'required',
            'shop_id' => 'required',
            'publish_status' => Rule::in(array_keys(Goods::$publishStatusMap))
        ];
    }

    public function messages()
    {
        return [
            'publish_status.in' => ':attribute 必须是' . implode('|', array_keys(Goods::$publishStatusMap)) . '(' . implode('|', array_values(Goods::$publishStatusMap)) . ')',
        ];
    }
}
