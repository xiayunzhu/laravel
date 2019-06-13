<?php

namespace App\Http\Requests\WeChat\Order;

use App\Http\Requests\WeChat\BaseRequest;

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
//            "shop_name" => "required",
//            "shop_nick" => "required",
            "total_fee" => "required",
//            "discount_fee" => "required",
            "express_price" => "required",
            "express_company" => "nullable",
            "buyer_msg" => "nullable",
            "seller_msg" => "nullable",
//            "buyer" => "required",
//            "buyer_id" => "required",
//            "shop_id" => "required",
            'receiver' => 'required',
            'mobile' => 'required',
            'phone' => 'nullable',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'detail' => 'required',
//            'create_time' => 'required',
            'order_items' => 'required|array',
            'order_items.*.goods_spec_id' => 'required',
            'order_items.*.num' => 'required',
//            'order_items.*.goods_id' => 'required',
//            'order_items.*.goods_name' => 'required',
//            'order_items.*.image_url' => 'required',
//            'order_items.*.deduct_stock_type' => 'required',
//            'order_items.*.spec_code' => 'required',
//            'order_items.*.goods_no' => 'required',
//            'order_items.*.goods_price' => 'required',
//            'order_items.*.line_price' => 'required',
//            'order_items.*.weight' => 'required',
//            'order_items.*.receivable' => 'required',
//            'order_items.*.payment' => 'required',
//            'order_items.*.buyer_id' => 'required',
//            'order_items.*.shop_id' => 'required',
//            'order_items.*.create_time' => 'required',
//            'order_items.*.status' => 'required',
//            'order_items.*.has_refund' => 'required',
        ];
    }
}
