<?php

namespace App\Http\Requests\Api\BuyerAddress;

use App\Http\Requests\Api\BaseRequest;

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
            'id' => 'required',
            'is_default' => 'required',
        ];
    }


}
