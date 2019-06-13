<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/27
 * Time: 10:26
 */

namespace App\Http\Requests\Api\Turnover;


use App\Http\Requests\Api\BaseRequest;

class MonthRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'shop_id' => 'required',
            'month' => 'required',
        ];
    }
}