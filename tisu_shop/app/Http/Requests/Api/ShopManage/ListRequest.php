<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/12
 * Time: 17:47
 */

namespace App\Http\Requests\Api\ShopManage;


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
        ];
    }
}