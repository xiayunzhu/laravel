<?php

namespace App\Http\Requests\Api\GoodsGroup;

use App\Http\Requests\Api\BaseRequest;

class DetailRequest extends BaseRequest
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
        ];
    }
}
