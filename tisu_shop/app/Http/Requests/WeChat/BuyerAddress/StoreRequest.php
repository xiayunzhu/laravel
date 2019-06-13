<?php

namespace App\Http\Requests\WeChat\BuyerAddress;

use App\Http\Requests\WeChat\BaseRequest;
use Illuminate\Validation\Rule;

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
            'receiver' => 'required|max:255',
            'mobile' => 'required|between:11,11',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'detail' => 'required',
            'buyer_id' => 'required',
            'shop_id' => 'required',
            'zip_code' => 'nullable',
            'is_default' => Rule::in([0, 1]),
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => ':attribute 手机号码必填',
            'mobile.between' => ':attribute 手机号码必须是11位'
        ];
    }
}
