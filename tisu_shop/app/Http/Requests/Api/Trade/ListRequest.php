<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 14:32
 */

namespace App\Http\Requests\Api\Trade;


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
            'begin_time'=>'required',
            'end_time'=>'required',
        ];
    }

        public function messages()
    {
        return [
            'shop_id.required'=>'店铺id 必传',
            'begin_time.required'=>'开始时间不能为空',
            'end_time.required'=>'结束时间不能为空',

        ];

    }
}