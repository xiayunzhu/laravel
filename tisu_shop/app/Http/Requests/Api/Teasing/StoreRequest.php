<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/16
 * Time: 10:29
 */

namespace App\Http\Requests\Api\Teasing;


use App\Http\Requests\Api\BaseRequest;

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
            'title' => 'required',
            'content' => 'required',

        ];
    }
}