<?php

namespace App\Http\Requests\Api\Login;

use App\Http\Requests\Api\BaseRequest;

class loginByVfCodeRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required',
            'v_code' => 'required',
        ];
    }
}
