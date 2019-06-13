<?php

namespace App\Http\Requests\Api\Shop;

use App\Http\Requests\Api\BaseRequest;

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
