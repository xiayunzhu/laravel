<?php

namespace App\Http\Requests\Api\GoodsImage;

use App\Http\Requests\Api\BaseRequest;
use App\Models\GoodsImage;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'is_show' => 'required|'.Rule::in(array_keys(GoodsImage::$isShowMap))
        ];
    }

    public function messages()
    {
        return [
            'is_show.in' => ':attribute 必须是' . implode('|', array_keys(GoodsImage::$isShowMap)) . '(' . implode('|', array_values(GoodsImage::$isShowMap)) . ')',
        ];
    }


}
