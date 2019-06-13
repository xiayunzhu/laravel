<?php

namespace App\Http\Requests\WeChat\Refund;

use App\Http\Requests\WeChat\BaseRequest;
use App\Models\Refund;
use Illuminate\Validation\Rule;
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
            'item_no' => 'required',
            'back_money' => 'required',
            'phone' => 'required',
            'image_urls' => 'array|max:4',
            'refund_reason' => Rule::in(array_keys(Refund::$refundReasonMap)),
            'refund_way' => Rule::in(array_keys(Refund::$refundWayMap)),
        ];
    }
    public function messages()
    {
        return [
            'refund_reason.in' => ':attribute 必须是' . implode('|', array_keys(Refund::$refundReasonMap)) . '(' . implode('|', array_values(Refund::$refundReasonMap)) . ')',
            'refund_way.in' => ':attribute 必须是' . implode('|', array_keys(Refund::$refundWayMap)) . '(' . implode('|', array_values(Refund::$refundWayMap)) . ')',
        ];
    }


}
