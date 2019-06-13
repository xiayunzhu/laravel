<?php

namespace App\Http\Requests\Admin\OrgGood;

use App\Http\Requests\Admin\BaseRequest;

class CreateUpdateRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //根据路由设置不同的规则
        if ($this->routeIs('admin.orgGoods.store')) {
            return [
                'category_id' => 'required',

            ];
        }
//        elseif ($this->routeIs('admin.orgGoods.update')) {
//            return [
//                'name' => 'required|between:1,25|unique:users,name,' . request('user')->id,//|regex:/^[A-Za-z0-9\-\_]+$/
//                'email' => 'required_without:phone|unique:users,email,' . request('user')->id,
//                'phone' => 'required_without:email|unique:users,phone,' . request('user')->id,
//            ];
//        }
    }
}
