<?php

namespace App\Http\Requests\WeChat\Buyer;

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
            'open_id' => 'required',
            'phone' => 'required',
            'union_id' => 'nullable',
            'nick_name' => 'required',
            'avatar_url' => 'required',
            'gender' => 'nullable',
            'language' => 'required',
            'country' => 'required',
            'province' => 'required',
            'city' => 'required',
            'shop_id' => 'required',
            'remark' => 'nullable',
        ];
    }
}
