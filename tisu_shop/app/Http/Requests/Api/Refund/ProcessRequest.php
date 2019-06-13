<?php

namespace App\Http\Requests\Api\Refund;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;
use App\Models\Refund;

class ProcessRequest extends BaseRequest
{


    private $rule = [] ;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rule = Refund::$processMap;
        return [
            'id' => 'required',
            'handle' => Rule::in(array_keys($this->rule)),
        ];
    }

    public function messages()
    {
        $this->rule = Refund::$processMap;
        return [
            'handle.in' => ':attribute 参数必须是' . implode('|', array_keys($this->rule)) . '(' . implode('|', array_values($this->rule)) . ')',
        ];
    }
}
