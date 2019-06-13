<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderAddress;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrderAddressesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['order_no', 'receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'buyer_id', 'create_time', 'shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "order_no" => "主订单编号", "receiver" => "收件人", "mobile" => "座机", "phone" => "手机", "province" => "省", "city" => "市", "district" => "区", "detail" => "详细地址", "buyer_id" => "买家id", "create_time" => "创建时间", "shop_id" => "店铺ID", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrderAddress $orderAddress
     * @return mixed
     */
    public function index(Request $request, OrderAddress $orderAddress)
    {
        return $this->backend_view('orderAddresses.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrderAddress::query();

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
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param OrderAddress $orderAddress
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrderAddress $orderAddress)
    {

        return $this->backend_view('orderAddresses.create_edit', compact('orderAddress'));
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
            $model = OrderAddress::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrderAddress $orderAddress
     * @return mixed
     */
    public function edit(OrderAddress $orderAddress)
    {

        return $this->backend_view('orderAddresses.create_edit', compact('orderAddress'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OrderAddress $orderAddress
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrderAddress $orderAddress, Result $result)
    {
        try {
            $orderAddress->update($request->only($this->fields));
            $result->succeed($orderAddress);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrderAddress $orderAddress
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrderAddress $orderAddress, Result $result)
    {
        if (!$orderAddress) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orderAddress->delete();
            if ($del) {
                $result->succeed($orderAddress);
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
            $dels = OrderAddress::whereIn('id', $ids)->delete();
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

//## 路由：OrderAddress
//$router->get('orderAddresses', 'OrderAddressesController@index')->name('admin.orderAddresses');
//$router->get('orderAddresses/create', 'OrderAddressesController@create')->name('admin.orderAddresses.create');
//$router->get('orderAddresses/list', 'OrderAddressesController@list')->name('admin.orderAddresses.list');
//$router->post('orderAddresses/store', 'OrderAddressesController@store')->name('admin.orderAddresses.store');
//$router->get('orderAddresses/edit/{orderAddress}', 'OrderAddressesController@edit')->name('admin.orderAddresses.edit');//隐式绑定
//$router->post('orderAddresses/update/{orderAddress}', 'OrderAddressesController@update')->name('admin.orderAddresses.update');//隐式绑定
//$router->get('orderAddresses/destroy/{orderAddress}', 'OrderAddressesController@destroy')->name('admin.orderAddresses.destroy');//隐式绑定
//$router->post('orderAddresses/destroyBat', 'OrderAddressesController@destroyBat')->name('admin.orderAddresses.destroyBat');

}
