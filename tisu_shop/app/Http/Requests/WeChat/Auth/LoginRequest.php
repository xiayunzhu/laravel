<?php

namespace App\Http\Requests\WeChat\Auth;

use App\Http\Requests\WeChat\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
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
            'app_id' => 'required',
        ];
    }
}
