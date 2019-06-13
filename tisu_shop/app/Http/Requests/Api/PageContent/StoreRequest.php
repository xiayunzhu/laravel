<?php

namespace App\Http\Requests\Api\PageContent;

use App\Http\Requests\Api\BaseRequest;
use App\Models\PageContent;
use Illuminate\Validation\Rule;
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
            'shop_id' => 'required',
            'image_url' => 'required',
            'title' => 'required',
            'describe' => 'required',
            'type' => Rule::in(array_keys(PageContent::$pageContentTypeMap))
        ];
    }
    public function messages()
    {
        return [
            'type.in' => ':attribute 必须是' . implode('|', array_keys(PageContent::$pageContentTypeMap)) . '(' . implode('|', array_values(PageContent::$pageContentTypeMap)) . ')',
        ];
    }

}
