<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 16:39
 */

namespace App\Http\Requests\Api\BuyerCoupon;


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
            'shop_id' => 'required',
            'buyer_id' => 'required',
        ];
    }
}