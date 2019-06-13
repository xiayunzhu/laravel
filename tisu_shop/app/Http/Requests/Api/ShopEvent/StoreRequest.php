<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 12:33
 */

namespace App\Http\Requests\Api\ShopEvent;


use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'event_id'=>'required|exists:events,id'
        ];
    }
}