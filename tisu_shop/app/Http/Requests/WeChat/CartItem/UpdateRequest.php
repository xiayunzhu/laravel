<?php

namespace App\Http\Requests\WeChat\CartItem;

use App\Http\Requests\WeChat\BaseRequest;

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
//            'id' => 'required',
            'num' => 'required'
        ];
    }
}
