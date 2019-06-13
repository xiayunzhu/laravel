<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/13
 * Time: 16:20
 */

namespace App\Http\Requests\Api\PromoItem;


use App\Http\Requests\Api\BaseRequest;

class UpperRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'promo_id' => 'required|exists:promos,id',
            'org_goods_ids'=>'required',
            'shop_id'=>'required|exists:shops,id',
        ];
    }
}