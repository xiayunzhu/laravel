<?php

namespace App\Http\Requests\WeChat\Auth;

use App\Http\Requests\WeChat\BaseRequest;

class AuthorizationRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'iv' => 'required',
            'encryptedData' => 'required',
            'app_id' => 'required',
        ];
    }
}
