<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrdersController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['order_no', 'shop_name', 'shop_nick', 'source', 'total_fee', 'paid_fee', 'discount_fee', 'post_fee', 'service_fee', 'pay_status', 'pay_time', 'express_price', 'express_company', 'express_no', 'send_status', 'send_time', 'receipt_status', 'receipt_time', 'refund_status', 'order_status', 'order_type', 'close_type', 'close_time', 'create_time', 'update_time', 'buyer_msg', 'seller_msg', 'buyer', 'buyer_id', 'shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "order_no" => "主订单编号", "shop_name" => "店铺名称", "shop_nick" => "店铺昵称", "source" => "来源", "total_fee" => "订单总金额", "paid_fee" => "实际支付金额", "discount_fee" => "优惠金额", "post_fee" => "邮费", "service_fee" => "服务费", "pay_status" => "付款状态", "pay_time" => "付款时间", "express_price" => "快递费用", "express_company" => "快递公司", "express_no" => "快递单号", "send_status" => "发货状态-0: 待发货,1: 已发货", "send_time" => "发货时间", "receipt_status" => "收货状态-0: 待收获 1: 已收货", "receipt_time" => "收货时间", "refund_status" => "退款状态-0: 无退款，1: 有退款", "order_status" => "订单状态 - WAIT：等待付款，PAYED：已付款,待发货，SEND_FEW：部分发货，SEND_ALL：已发货(全)，WAIT_CONFIRM：待确认收货,DONE：确认收货，CLOSE：关闭订单", "order_type" => "订单类型 - 0: 普通订单", "close_type" => "订单类型 - 0: 未关闭,1: 过期关闭,2: 标记退款,3: 超时订单取消,4: 买家取消,5: 卖家取消,6: 部分退款,10: 无法联系上卖家,11: 卖家误拍或重拍,12: 买家无诚意完成交易,13: 商品缺货无法交易", "close_time" => "订单关闭时间", "create_time" => "创建时间", "update_time" => "更新时间", "buyer_msg" => "买家备注", "seller_msg" => "卖家备注", "buyer" => "买家昵称", "buyer_id" => "买家ID", "shop_id" => "店铺ID", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Order $order
     * @return mixed
     */
    public function index(Request $request, Order $order)
    {
        return $this->backend_view('orders.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Order::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
//        $data->withPath($request->fullUrl());
        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Order $order)
    {

        return $this->backend_view('orders.create_edit', compact('order'));
    }

    /**
     * 添加
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function store(Request $request, Result $result)
    {
        try {
            $model = Order::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Order $order
     * @return mixed
     */
    public function edit(Order $order)
    {

        return $this->backend_view('orders.create_edit', compact('order'));
    }

    /**
     * 编辑
     *
     * @param Order $order
     * @return mixed
     */
    public function detail(Order $order)
    {
        $order->load(['order_items', 'address','order_items.goods.logo_image']);
        return $this->backend_view('orders.detail', compact('order'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Order $order
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Order $order, Result $result)
    {
        try {
            $order->update($request->only($this->fields));
            $result->succeed($order);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Order $order
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Order $order, Result $result)
    {
        if (!$order) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $order->delete();
            if ($del) {
                $result->succeed($order);
            } else {
                $result->failed('删除失败');
            }
        }

        return $result->toArray();
    }


    /**
     * 批量删除
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function destroyBat(Request $request, Result $result)
    {
        $ids = $request->get('ids');
        if ($ids && is_array($ids)) {
            $dels = Order::whereIn('id', $ids)->delete();
            if ($dels > 0) {
                $result->succeed();
            } else {
                $result->failed('删除失败');
            }
        } else {
            $result->failed('参数错误');
        }

        return $result->toArray();
    }

//## 路由：Order
//$router->get('orders', 'OrdersController@index')->name('admin.orders');
//$router->get('orders/create', 'OrdersController@create')->name('admin.orders.create');
//$router->get('orders/list', 'OrdersController@list')->name('admin.orders.list');
//$router->post('orders/store', 'OrdersController@store')->name('admin.orders.store');
//$router->get('orders/edit/{order}', 'OrdersController@edit')->name('admin.orders.edit');//隐式绑定
//$router->post('orders/update/{order}', 'OrdersController@update')->name('admin.orders.update');//隐式绑定
//$router->get('orders/destroy/{order}', 'OrdersController@destroy')->name('admin.orders.destroy');//隐式绑定
//$router->post('orders/destroyBat', 'OrdersController@destroyBat')->name('admin.orders.destroyBat');

}
