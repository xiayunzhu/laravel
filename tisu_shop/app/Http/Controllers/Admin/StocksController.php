<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use Illuminate\Http\Request;
use Ml\Response\Result;

class StocksController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['available','item_code','modified','oln_item_id','oln_sku_id','quantity','sku_code','storage_code','storage_name'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["available"=>"可用库存","created_at"=>"创建时间","id"=>"ID","item_code"=>"商品编码","modified"=>"库存修改时间","oln_item_id"=>"线上商品编号，即 B2C 推送到 ERP 中的 itemID","oln_sku_id"=>"线上规格编号","quantity"=>"实际库存","sku_code"=>"系统规格编码","storage_code"=>"仓库编码","storage_name"=>"仓库名称","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Stock $stock
     * @return mixed
     */
    public function index(Request $request, Stock $stock)
    {
        return $this->backend_view('stocks.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Stock::query();

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
     * @param Stock $stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Stock $stock)
    {

        return $this->backend_view('stocks.create_edit', compact('stock'));
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
            $model = Stock::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Stock $stock
     * @return mixed
     */
    public function edit(Stock $stock)
    {

        return $this->backend_view('stocks.create_edit', compact('stock'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Stock $stock
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Stock $stock, Result $result)
    {
        try {
            $stock->update($request->only($this->fields));
            $result->succeed($stock);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Stock $stock
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Stock $stock, Result $result)
    {
        if (!$stock) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $stock->delete();
            if ($del) {
                $result->succeed($stock);
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
            $dels = Stock::whereIn('id', $ids)->delete();
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

//## 路由：Stock
//$router->get('stocks', 'StocksController@index')->name('admin.stocks');
//$router->get('stocks/create', 'StocksController@create')->name('admin.stocks.create');
//$router->get('stocks/list', 'StocksController@list')->name('admin.stocks.list');
//$router->post('stocks/store', 'StocksController@store')->name('admin.stocks.store');
//$router->get('stocks/edit/{stock}', 'StocksController@edit')->name('admin.stocks.edit');//隐式绑定
//$router->post('stocks/update/{stock}', 'StocksController@update')->name('admin.stocks.update');//隐式绑定
//$router->get('stocks/destroy/{stock}', 'StocksController@destroy')->name('admin.stocks.destroy');//隐式绑定
//$router->post('stocks/destroyBat', 'StocksController@destroyBat')->name('admin.stocks.destroyBat');

}
