<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrderItemsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['order_no','item_no','goods_id','goods_name','image_url','deduct_stock_type','spec_type','spec_code','goods_spec_id','goods_no','goods_price','line_price','weight','num','receivable','payment','buyer_id','shop_id','create_time','status','has_refund'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","order_no"=>"主订单编号","item_no"=>"子订单编号","goods_id"=>"商品ID","goods_name"=>"商品名称","image_url"=>"商品图片地址","deduct_stock_type"=>"扣减库存的方式","spec_type"=>"规格类型","spec_code"=>"规格编码","goods_spec_id"=>"商品规格ID","goods_no"=>"商品编号","goods_price"=>"商品价格","line_price"=>"商品划线价格","weight"=>"商品重量KG","num"=>"数量","receivable"=>"应付金额(line_price*num)","payment"=>"实付金额(goods_price*num)","buyer_id"=>"买家id","shop_id"=>"店铺ID","create_time"=>"创建时间","status"=>"明细状态","has_refund"=>"是否为退款/退货明细-0：无退款；1：有退款","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrderItem $orderItem
     * @return mixed
     */
    public function index(Request $request, OrderItem $orderItem)
    {
        return $this->backend_view('orderItems.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrderItem::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
               if(!empty($value)){
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
     * @param OrderItem $orderItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrderItem $orderItem)
    {

        return $this->backend_view('orderItems.create_edit', compact('orderItem'));
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
            $model = OrderItem::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrderItem $orderItem
     * @return mixed
     */
    public function edit(OrderItem $orderItem)
    {

        return $this->backend_view('orderItems.create_edit', compact('orderItem'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OrderItem $orderItem
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrderItem $orderItem, Result $result)
    {
        try {
            $orderItem->update($request->only($this->fields));
            $result->succeed($orderItem);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrderItem $orderItem
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrderItem $orderItem, Result $result)
    {
        if (!$orderItem) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orderItem->delete();
            if ($del) {
                $result->succeed($orderItem);
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
            $dels = OrderItem::whereIn('id', $ids)->delete();
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

//## 路由：OrderItem
//$router->get('orderItems', 'OrderItemsController@index')->name('admin.orderItems');
//$router->get('orderItems/create', 'OrderItemsController@create')->name('admin.orderItems.create');
//$router->get('orderItems/list', 'OrderItemsController@list')->name('admin.orderItems.list');
//$router->post('orderItems/store', 'OrderItemsController@store')->name('admin.orderItems.store');
//$router->get('orderItems/edit/{orderItem}', 'OrderItemsController@edit')->name('admin.orderItems.edit');//隐式绑定
//$router->post('orderItems/update/{orderItem}', 'OrderItemsController@update')->name('admin.orderItems.update');//隐式绑定
//$router->get('orderItems/destroy/{orderItem}', 'OrderItemsController@destroy')->name('admin.orderItems.destroy');//隐式绑定
//$router->post('orderItems/destroyBat', 'OrderItemsController@destroyBat')->name('admin.orderItems.destroyBat');

}
