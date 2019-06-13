<?php

namespace App\Http\Requests\Api\Promo;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;
use App\Models\Promo;

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
            'shop_id' => 'required',
            'type' => Rule::in(array_keys(Promo::$promoTypeMap)),
            'proceed_status' => Rule::in(array_keys(Promo::$proceedStatusMap)),
        ];
    }
    public function messages()
    {

        return [
            'type.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$promoTypeMap)) . '(' . implode('|', array_values(Promo::$promoTypeMap)) . ')',
            'proceed_status.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$proceedStatusMap)) . '(' . implode('|', array_values(Promo::$proceedStatusMap)) . ')',
        ];
    }

}
