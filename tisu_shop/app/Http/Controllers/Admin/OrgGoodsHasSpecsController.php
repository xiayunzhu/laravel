<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrgGoodsHasSpec;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrgGoodsHasSpecsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['org_goods_id','spec_id','spec_value_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","org_goods_id"=>"商品ID","spec_id"=>"规格属性ID","spec_value_id"=>"规格属性值ID","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrgGoodsHasSpec $orgGoodsHasSpec
     * @return mixed
     */
    public function index(Request $request, OrgGoodsHasSpec $orgGoodsHasSpec)
    {
        return $this->backend_view('orgGoodsHasSpecs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrgGoodsHasSpec::query();

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
     * @param OrgGoodsHasSpec $orgGoodsHasSpec
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrgGoodsHasSpec $orgGoodsHasSpec)
    {

        return $this->backend_view('orgGoodsHasSpecs.create_edit', compact('orgGoodsHasSpec'));
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
            $model = OrgGoodsHasSpec::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrgGoodsHasSpec $orgGoodsHasSpec
     * @return mixed
     */
    public function edit(OrgGoodsHasSpec $orgGoodsHasSpec)
    {

        return $this->backend_view('orgGoodsHasSpecs.create_edit', compact('orgGoodsHasSpec'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OrgGoodsHasSpec $orgGoodsHasSpec
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrgGoodsHasSpec $orgGoodsHasSpec, Result $result)
    {
        try {
            $orgGoodsHasSpec->update($request->only($this->fields));
            $result->succeed($orgGoodsHasSpec);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrgGoodsHasSpec $orgGoodsHasSpec
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrgGoodsHasSpec $orgGoodsHasSpec, Result $result)
    {
        if (!$orgGoodsHasSpec) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orgGoodsHasSpec->delete();
            if ($del) {
                $result->succeed($orgGoodsHasSpec);
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
            $dels = OrgGoodsHasSpec::whereIn('id', $ids)->delete();
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

//## 路由：OrgGoodsHasSpec
//$router->get('orgGoodsHasSpecs', 'OrgGoodsHasSpecsController@index')->name('admin.orgGoodsHasSpecs');
//$router->get('orgGoodsHasSpecs/create', 'OrgGoodsHasSpecsController@create')->name('admin.orgGoodsHasSpecs.create');
//$router->get('orgGoodsHasSpecs/list', 'OrgGoodsHasSpecsController@list')->name('admin.orgGoodsHasSpecs.list');
//$router->post('orgGoodsHasSpecs/store', 'OrgGoodsHasSpecsController@store')->name('admin.orgGoodsHasSpecs.store');
//$router->get('orgGoodsHasSpecs/edit/{orgGoodsHasSpec}', 'OrgGoodsHasSpecsController@edit')->name('admin.orgGoodsHasSpecs.edit');//隐式绑定
//$router->post('orgGoodsHasSpecs/update/{orgGoodsHasSpec}', 'OrgGoodsHasSpecsController@update')->name('admin.orgGoodsHasSpecs.update');//隐式绑定
//$router->get('orgGoodsHasSpecs/destroy/{orgGoodsHasSpec}', 'OrgGoodsHasSpecsController@destroy')->name('admin.orgGoodsHasSpecs.destroy');//隐式绑定
//$router->post('orgGoodsHasSpecs/destroyBat', 'OrgGoodsHasSpecsController@destroyBat')->name('admin.orgGoodsHasSpecs.destroyBat');

}
