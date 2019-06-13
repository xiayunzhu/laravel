<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 14:32
 */

namespace App\Http\Requests\Api\Turnover;


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
            'begin_time' => 'required',
            'end_time' => 'required',
        ];
    }
}