<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 20:00
 */

namespace App\Http\Requests\Api\Promo;


use App\Http\Requests\Api\BaseRequest;

class ItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'promo_id' => 'required|exists:promos,id',
            'goods_ids'=>'required'
        ];
    }
}