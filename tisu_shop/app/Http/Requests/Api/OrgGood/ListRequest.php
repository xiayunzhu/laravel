<?php

namespace App\Http\Requests\Api\OrgGood;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class ListRequest extends BaseRequest
{

    private $map;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->map = ['commissionDesc' => '佣金降序', 'commissionAsc' => '佣金升序', 'priceDesc' => '价格降序', 'priceAsc' => '价格升序', 'all' => '综合', 'newProduct' => '新品'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sorting' => Rule::in(array_keys($this->map)),
        ];
    }

    public function messages()
    {
        return [
            'sorting.in' => ':attribute 必须是' . implode('|', array_keys($this->map)) . '(' . implode('|', array_values($this->map)) . ')',
        ];
    }


}
