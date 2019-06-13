<?php

namespace App\Http\Requests\Api\Refund;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Refund;
use Illuminate\Validation\Rule;

class ListAppRequest extends BaseRequest
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
            'refund_progress' => Rule::in(array_keys(Refund::$refundProgressAPPStatus)),
            'page' => 'numeric',
            'per_page' => 'numeric',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'refund_progress.in' => ':attribute 必须是' . implode('|', array_keys(Refund::$refundProgressAPPStatus)) . '(' . implode('|', array_values(Refund::$refundProgressAPPStatus)) . ')',
        ];
    }
}
