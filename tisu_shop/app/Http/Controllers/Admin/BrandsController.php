<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\BrandsHandler;
use App\Models\Brand;
use Illuminate\Http\Request;
use Ml\Response\Result;

class BrandsController extends BaseController
{


    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['name','country'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","name"=>"名称","country"=>"国家","deleted_at"=>"删除时间","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Brand $brand
     * @return mixed
     */
    public function index(Request $request, Brand $brand)
    {


        return $this->backend_view('brands.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Brand::query();

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
     * @param Brand $brand
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Brand $brand)
    {


        return $this->backend_view('brands.create_edit', compact('brand'));
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
            $model = Brand::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Brand $brand
     * @return mixed
     */
    public function edit(Brand $brand)
    {

        return $this->backend_view('brands.create_edit', compact('brand'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Brand $brand
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Brand $brand, Result $result)
    {
        try {
            $brand->update($request->only($this->fields));
            $result->succeed($brand);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Brand $brand
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Brand $brand, Result $result)
    {
        if (!$brand) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $brand->delete();
            if ($del) {
                $result->succeed($brand);
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
            $dels = Brand::whereIn('id', $ids)->delete();
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

//## 路由：Brand
//$router->get('brands', 'BrandsController@index')->name('admin.brands');
//$router->get('brands/create', 'BrandsController@create')->name('admin.brands.create');
//$router->get('brands/list', 'BrandsController@list')->name('admin.brands.list');
//$router->post('brands/store', 'BrandsController@store')->name('admin.brands.store');
//$router->get('brands/edit/{brand}', 'BrandsController@edit')->name('admin.brands.edit');//隐式绑定
//$router->post('brands/update/{brand}', 'BrandsController@update')->name('admin.brands.update');//隐式绑定
//$router->get('brands/destroy/{brand}', 'BrandsController@destroy')->name('admin.brands.destroy');//隐式绑定
//$router->post('brands/destroyBat', 'BrandsController@destroyBat')->name('admin.brands.destroyBat');

}
