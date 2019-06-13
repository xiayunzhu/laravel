<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/13
 * Time: 14:11
 */

namespace App\Http\Requests\Api\Event;


use App\Http\Requests\Api\BaseRequest;

class IdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id' => 'required'
        ];
    }
}