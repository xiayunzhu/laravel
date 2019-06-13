<?php

namespace App\Http\Requests\WeChat\DeliveryRules;

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
            'city_name' => 'required',
            'order_items' => 'required|array',
            'order_items.*.goods_spec_id' => 'required',
            'order_items.*.num' => 'required',
        ];
    }
}
