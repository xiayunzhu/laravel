<?php

namespace App\Http\Requests\Api\Promo;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;
use App\Models\Promo;

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
            'shop_id' => 'required',
            'title' => 'required',
            'discount' => 'required|numeric',
            'type' => 'required|'.Rule::in(array_keys(Promo::$promoTypeMap)),
            'require_threshold' => 'required',
            'credit_limit' => 'required_if:type,DISCOUNT_COUPON',
            'range' => 'required|'.Rule::in(array_keys(Promo::$goodRangeMap)),
            'good_ids' => 'required_if:range,PART_CAN,PART_CANT',
            'total_count' => 'required|numeric',
            'apply_user' => 'required|'.Rule::in(array_keys(Promo::$userTypeMap)),
            'tickets_available' => 'required_if:apply_user,ALL',
            'take_begin' => 'required|date',
            'take_end' => 'required|date',
            'validity_type' => 'required|'.Rule::in(array_keys(Promo::$validityMap)),
            'effect_time' => 'required_if:validity_type,APPOINT_DATE|nullable|date',
            'invalid_time' => 'required_if:validity_type,APPOINT_DATE|nullable|date',
            'days' => 'required_if:validity_type,APPOINT_DURING|numeric',
            'explain' => 'required',
        ];
    }

    public function messages()
    {

        return [
            'type.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$promoTypeMap)) . '(' . implode('|', array_values(Promo::$promoTypeMap)) . ')',
            'range.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$goodRangeMap)) . '(' . implode('|', array_values(Promo::$goodRangeMap)) . ')',
            'apply_user.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$userTypeMap)) . '(' . implode('|', array_values(Promo::$userTypeMap)) . ')',
            'validity_type.in' => ':attribute 参数必须是' . implode('|', array_keys(Promo::$validityMap)) . '(' . implode('|', array_values(Promo::$validityMap)) . ')',
        ];
    }
}
