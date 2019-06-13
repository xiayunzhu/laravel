<?php

namespace App\Http\Requests\Api\PageContentGood;

use App\Http\Requests\Api\BaseRequest;

class ListRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page_contents_id' => 'required'
        ];
    }

}
