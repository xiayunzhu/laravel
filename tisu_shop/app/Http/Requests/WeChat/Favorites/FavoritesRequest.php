<?php

namespace App\Http\Requests\WeChat\Favorites;

use App\Http\Requests\WeChat\BaseRequest;

class FavoritesRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'buyer_id' => 'required',
            'shop_id' => 'required',
            'goods_id' => 'required',
        ];
    }
}
