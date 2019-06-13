<?php

namespace App\Http\Requests\Api\Message;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Message;
use Illuminate\Foundation\Http\FormRequest;
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
            'content' => 'required',
            'details' => 'required',
            'type' => Rule::in(array_keys(Message::$typeMap)),
            'status' => Rule::in(array_keys(Message::$statusMap)),
        ];
    }
}
