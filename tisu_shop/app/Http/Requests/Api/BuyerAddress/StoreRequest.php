<?php

namespace App\Http\Requests\Api\BuyerAddress;

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
            'receiver' => 'required|max:255',
            'mobile' => 'required|between:11,11',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'detail' => 'required',
            'buyer_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => ':attribute 手机号码必填'
        ];
    }
}
