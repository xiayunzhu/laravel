<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/13
 * Time: 14:26
 */

namespace App\Http\Requests\PromoItem;


use App\Http\Requests\Api\BaseRequest;

class ListRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [

            'promo_id' => 'required',
            'type' => 'required'

        ];
    }
}