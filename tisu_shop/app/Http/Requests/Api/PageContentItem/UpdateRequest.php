<?php

namespace App\Http\Requests\Api\PageContentItem;

use App\Http\Requests\Api\BaseRequest;
use App\Models\PageContentsItem;
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
            'is_show' => Rule::in(array_keys(PageContentsItem::$isShowMap))
        ];
    }

    public function messages()
    {
        return [
            'is_show.in' => ':attribute 必须是' . implode('|', array_keys(PageContentsItem::$isShowMap)) . '(' . implode('|', array_values(PageContentsItem::$isShowMap)) . ')',
        ];
    }


}
