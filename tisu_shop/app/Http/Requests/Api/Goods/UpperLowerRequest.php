<?php

namespace App\Http\Requests\Api\Goods;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class UpperLowerRequest extends BaseRequest
{

    private $handle;
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->handle = [
            'upper'=>'上架',
            'lower'=>'下架',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'goods_id' => 'required',
            'handle' => Rule::in(array_keys($this->handle))
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'handle.in' => ':attribute 必须是' . implode('|', array_keys($this->handle)) . '(' . implode('|', array_values($this->handle)) . ')',
        ];
    }
}
