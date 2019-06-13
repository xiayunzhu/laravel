<?php
namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\Api\BaseRequest;
class SpecRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'goods_id' => 'required',
            'color' => 'required',
            'size' => 'required',
        ];
    }

}