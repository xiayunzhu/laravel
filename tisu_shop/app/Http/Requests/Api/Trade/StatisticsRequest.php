<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 14:32
 */

namespace App\Http\Requests\Api\Trade;


use App\Http\Requests\Api\BaseRequest;

class StatisticsRequest extends BaseRequest
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
            'time'=>'required',
            'page_view'=>'required|integer',
        ];
    }

        public function messages()
    {
        return [
            'shop_id.required'=>'店铺id 必传',
            'time.required'=>'日期必传',
            'page_view.required'=>'浏览量必传',
            'page_view.integer'=>'浏览量为整数',
        ];

    }
}