<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 16:35
 */

namespace App\Http\Requests\Api\Shop;


use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'shop_id' => 'required',
        ];
    }
}