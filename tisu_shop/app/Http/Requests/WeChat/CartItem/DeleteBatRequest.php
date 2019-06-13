<?php

namespace App\Http\Requests\WeChat\CartItem;

use App\Http\Requests\WeChat\BaseRequest;

class DeleteBatRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => 'required|array|max:20'
        ];
    }
}
