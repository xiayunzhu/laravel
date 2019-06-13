<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 17:30
 */

namespace App\Http\Requests\WeChat\BuyerCoupon;


use App\Http\Requests\WeChat\BaseRequest;


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
            'promo_id' => 'required',
            'buyer_id'=>'required'
        ];
    }
}