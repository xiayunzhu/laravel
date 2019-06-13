<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Ml\Response\Result;

class ProductsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['article_number', 'bar_code', 'color', 'item_code', 'item_name', 'other_prop', 'price', 'spec_code', 'status', 'unit'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["article_number" => "货号", "bar_code" => "商品条码", "color" => "颜色", "created_at" => "创建时间", "id" => "ID", "item_code" => "商品编码", "item_name" => "商品名称", "other_prop" => "其他规格", "price" => "标价", "spec_code" => "规格编码", "status" => "状态", "unit" => "单位", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function index(Request $request, Product $product)
    {

        return $this->backend_view('products.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Product::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false || strpos($field,'spec_code') !== false) {
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
        $data = $query->with(['stock'])->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Product $product)
    {

        return $this->backend_view('products.create_edit', compact('product'));
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
//            dd($request->all());
//            exit;

            $model = Product::create($request->only($this->fields));
            $result->succeed($model);
//            dd( $result->succeed($model));
//            exit;
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Product $product
     * @return mixed
     */
    public function edit(Product $product)
    {

        return $this->backend_view('products.create_edit', compact('product'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Product $product
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Product $product, Result $result)
    {
        try {
            $product->update($request->only($this->fields));
            $result->succeed($product);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Product $product
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Product $product, Result $result)
    {
        if (!$product) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $product->delete();
            if ($del) {
                $result->succeed($product);
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
            $dels = Product::whereIn('id', $ids)->delete();
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

//## 路由：Product
//$router->get('products', 'ProductsController@index')->name('admin.products');
//$router->get('products/create', 'ProductsController@create')->name('admin.products.create');
//$router->get('products/list', 'ProductsController@list')->name('admin.products.list');
//$router->post('products/store', 'ProductsController@store')->name('admin.products.store');
//$router->get('products/edit/{product}', 'ProductsController@edit')->name('admin.products.edit');//隐式绑定
//$router->post('products/update/{product}', 'ProductsController@update')->name('admin.products.update');//隐式绑定
//$router->get('products/destroy/{product}', 'ProductsController@destroy')->name('admin.products.destroy');//隐式绑定
//$router->post('products/destroyBat', 'ProductsController@destroyBat')->name('admin.products.destroyBat');

}
