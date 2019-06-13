<?php

namespace App\Http\Requests\Api\UploadFile;

use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'upload_file' => 'required',
        ];
    }
}
