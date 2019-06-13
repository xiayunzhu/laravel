<?php

namespace App\Http\Requests\WeChat\Refund;

use App\Http\Requests\WeChat\BaseRequest;
use App\Models\Refund;
use Illuminate\Validation\Rule;
class SaleAfterRequest extends BaseRequest
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
