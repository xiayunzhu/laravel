<?php

namespace App\Http\Requests\WeChat\Buyer;

use App\Http\Requests\WeChat\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class InfoRequest extends BaseRequest
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
            'shop_id' => 'required',
        ];
    }
}
