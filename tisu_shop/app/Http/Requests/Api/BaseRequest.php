<?php

namespace App\Http\Requests\Api;

use App\Lib\Response\Result;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 自定义检验失败返回结果
     *
     * 验证失败 200
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->messages();
        $error = $validator->errors()->first();
        $result = new Result();
        $result->failed($error);
        $result->setModel($errors);

        throw new HttpResponseException(response($result->toArray(), 200));
    }
}
