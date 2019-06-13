<?php

namespace App\Http\Controllers\Admin;

use App\Models\BuyerAddress;
use App\Models\Region;
use Illuminate\Http\Request;
use Ml\Response\Result;

class BuyerAddressesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'zip_code', 'is_default', 'buyer_id', 'shop_id', 'user_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "receiver" => "接受者", "mobile" => "可移动的", "phone" => "电话", "province" => "省份", "city" => "城市", "district" => "区", "detail" => "细节", "zip_code" => "齐普码", "is_default" => "缺省默认值", "buyer_id" => "布埃尔齐德", "shop_id" => "购物狂", "deleted_at" => "删除时间", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param BuyerAddress $buyerAddress
     * @return mixed
     */
    public function index(Request $request, BuyerAddress $buyerAddress)
    {
        return $this->backend_view('buyerAddresses.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = BuyerAddress::query();

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
        $data->load(['buyer', 'shop']);
//        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param BuyerAddress $buyerAddress
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(BuyerAddress $buyerAddress)
    {

        return $this->backend_view('buyerAddresses.create_edit', compact('buyerAddress', 'region'));
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
            $model = BuyerAddress::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param BuyerAddress $buyerAddress
     * @return mixed
     */
    public function edit(BuyerAddress $buyerAddress)
    {

        return $this->backend_view('buyerAddresses.create_edit', compact('buyerAddress'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param BuyerAddress $buyerAddress
     * @param Result $result
     * @return array
     */
    public function update(Request $request, BuyerAddress $buyerAddress, Result $result)
    {
        try {
            $buyerAddress->update($request->only($this->fields));
            $result->succeed($buyerAddress);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param BuyerAddress $buyerAddress
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(BuyerAddress $buyerAddress, Result $result)
    {
        if (!$buyerAddress) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $buyerAddress->delete();
            if ($del) {
                $result->succeed($buyerAddress);
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
            $dels = BuyerAddress::whereIn('id', $ids)->delete();
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

//## 路由：BuyerAddress
//$router->get('buyerAddresses', 'BuyerAddressesController@index')->name('admin.buyerAddresses');
//$router->get('buyerAddresses/create', 'BuyerAddressesController@create')->name('admin.buyerAddresses.create');
//$router->get('buyerAddresses/list', 'BuyerAddressesController@list')->name('admin.buyerAddresses.list');
//$router->post('buyerAddresses/store', 'BuyerAddressesController@store')->name('admin.buyerAddresses.store');
//$router->get('buyerAddresses/edit/{buyerAddress}', 'BuyerAddressesController@edit')->name('admin.buyerAddresses.edit');//隐式绑定
//$router->post('buyerAddresses/update/{buyerAddress}', 'BuyerAddressesController@update')->name('admin.buyerAddresses.update');//隐式绑定
//$router->get('buyerAddresses/destroy/{buyerAddress}', 'BuyerAddressesController@destroy')->name('admin.buyerAddresses.destroy');//隐式绑定
//$router->post('buyerAddresses/destroyBat', 'BuyerAddressesController@destroyBat')->name('admin.buyerAddresses.destroyBat');

}
