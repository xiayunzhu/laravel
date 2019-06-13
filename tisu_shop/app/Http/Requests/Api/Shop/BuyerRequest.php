<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 13:44
 */

namespace App\Http\Requests\Api\Shop;


use App\Http\Requests\Api\BaseRequest;

class BuyerRequest extends  BaseRequest
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
            'buyer_increase' => 'required',
            'data'=>'required'
        ];
    }
}