<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\BaseRequest;


class CancelOrderRequest extends BaseRequest
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
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'=>'订单id必传',
        ];
    }
}
