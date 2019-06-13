<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/16
 * Time: 11:36
 */

namespace App\Http\Requests\Api\Users;


use App\Http\Requests\Api\BaseRequest;

class ChangeRequest  extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|unique:users',
            'v_code' => 'required',
        ];
    }
}