<?php

namespace App\Http\Requests\Api\GoodsImage;

use App\Http\Requests\Api\BaseRequest;

class DeleteRequest extends BaseRequest
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
