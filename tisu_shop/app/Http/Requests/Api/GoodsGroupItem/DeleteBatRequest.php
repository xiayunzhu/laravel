<?php

namespace App\Http\Requests\Api\GoodsGroupItem;

use App\Http\Requests\Api\BaseRequest;

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
