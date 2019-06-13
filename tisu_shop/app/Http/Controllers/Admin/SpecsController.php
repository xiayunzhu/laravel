<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specs;
use Illuminate\Http\Request;
use Ml\Response\Result;

class SpecsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['spec_name'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","spec_name"=>"规格名称","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Specs $specs
     * @return mixed
     */
    public function index(Request $request, Specs $specs)
    {
        return $this->backend_view('specs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Specs::query();

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
     * @param Specs $specs
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Specs $specs)
    {

        return $this->backend_view('specs.create_edit', compact('specs'));
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
            $model = Specs::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Specs $specs
     * @return mixed
     */
    public function edit(Specs $specs)
    {

        return $this->backend_view('specs.create_edit', compact('specs'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Specs $specs
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Specs $specs, Result $result)
    {
        try {
            $specs->update($request->only($this->fields));
            $result->succeed($specs);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Specs $specs
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Specs $specs, Result $result)
    {
        if (!$specs) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $specs->delete();
            if ($del) {
                $result->succeed($specs);
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
            $dels = Specs::whereIn('id', $ids)->delete();
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

//## 路由：Specs
//$router->get('specs', 'SpecsController@index')->name('admin.specs');
//$router->get('specs/create', 'SpecsController@create')->name('admin.specs.create');
//$router->get('specs/list', 'SpecsController@list')->name('admin.specs.list');
//$router->post('specs/store', 'SpecsController@store')->name('admin.specs.store');
//$router->get('specs/edit/{specs}', 'SpecsController@edit')->name('admin.specs.edit');//隐式绑定
//$router->post('specs/update/{specs}', 'SpecsController@update')->name('admin.specs.update');//隐式绑定
//$router->get('specs/destroy/{specs}', 'SpecsController@destroy')->name('admin.specs.destroy');//隐式绑定
//$router->post('specs/destroyBat', 'SpecsController@destroyBat')->name('admin.specs.destroyBat');

}
