<?php

namespace App\Http\Requests\Api\GoodsImage;


use App\Http\Requests\Api\BaseRequest;



class PicMoveRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pics' => 'required|array',
        ];
    }
}
