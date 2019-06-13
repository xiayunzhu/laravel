<?php

namespace App\Http\Requests\Api\PageContentGood;

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
            'id' => 'required',
            'status'=>'required',

        ];
    }


}
