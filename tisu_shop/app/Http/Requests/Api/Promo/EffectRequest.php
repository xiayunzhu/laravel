<?php

namespace App\Http\Requests\Api\Promo;

use App\Http\Requests\Api\BaseRequest;


class EffectRequest extends BaseRequest
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
