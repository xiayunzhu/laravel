<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Goods;
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
            'category_id' => 'nullable',
            'sales_status' => Rule::in(array_keys(Goods::$saleStatusMap)),
            'publish_status' => Rule::in(array_keys(Goods::$publishStatusMap)),
        ];
    }

    public function messages()
    {
        return [
            'shop_id.required' => ':attribute 店铺ID 必传',
            'sales_status.in' => ':attribute 必须是' . implode('|', array_keys(Goods::$saleStatusMap)) . '(' . implode('|', array_values(Goods::$saleStatusMap)) . ')',
            'publish_status.in' => ':attribute 必须是' . implode('|', array_keys(Goods::$publishStatusMap)) . '(' . implode('|', array_values(Goods::$publishStatusMap)) . ')',
        ];
    }
}
