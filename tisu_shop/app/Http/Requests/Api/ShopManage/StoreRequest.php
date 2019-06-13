<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/12
 * Time: 18:47
 */

namespace App\Http\Requests\Api\ShopManage;


use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'shop_id' => 'required',
            'phone'=>'required',
            'v_code'=>'required'
        ];
    }
}