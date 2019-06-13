<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 14:50
 */

namespace App\Http\Requests\Api\Buyer;


use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => 'required',
            'remark' => 'required',
        ];
    }
}