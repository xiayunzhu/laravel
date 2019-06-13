<?php

namespace App\Http\Requests\WeChat\BuyerAddress;


use App\Http\Requests\WeChat\BaseRequest;
use App\Models\BuyerAddress;
use Illuminate\Validation\Rule;

class IsdefaultRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'id' => 'required',
            'is_default' => ['required', Rule::in(array_keys(BuyerAddress::$isDefaultMap))],
        ];
    }

    public function messages()
    {
        return [
            'is_default.in'=>':attribute 必须是' . implode('|', array_keys(BuyerAddress::$isDefaultMap)) . '(' . implode('|', array_values(BuyerAddress::$isDefaultMap)) . ')',
        ];
    }


}
