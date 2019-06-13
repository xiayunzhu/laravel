<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsGroup;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsGroupsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['name','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["created_at"=>"创建时间","deleted_at"=>"删除时间","id"=>"ID","name"=>"分组名称","shop_id"=>"归属店铺","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param GoodsGroup $goodsGroup
     * @return mixed
     */
    public function index(Request $request, GoodsGroup $goodsGroup)
    {
        return $this->backend_view('goodsGroups.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = GoodsGroup::query();

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
     * @param GoodsGroup $goodsGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(GoodsGroup $goodsGroup)
    {

        return $this->backend_view('goodsGroups.create_edit', compact('goodsGroup'));
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
            $model = GoodsGroup::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param GoodsGroup $goodsGroup
     * @return mixed
     */
    public function edit(GoodsGroup $goodsGroup)
    {

        return $this->backend_view('goodsGroups.create_edit', compact('goodsGroup'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param GoodsGroup $goodsGroup
     * @param Result $result
     * @return array
     */
    public function update(Request $request, GoodsGroup $goodsGroup, Result $result)
    {
        try {
            $goodsGroup->update($request->only($this->fields));
            $result->succeed($goodsGroup);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param GoodsGroup $goodsGroup
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(GoodsGroup $goodsGroup, Result $result)
    {
        if (!$goodsGroup) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goodsGroup->delete();
            if ($del) {
                $result->succeed($goodsGroup);
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
            $dels = GoodsGroup::whereIn('id', $ids)->delete();
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

//## 路由：GoodsGroup
//$router->get('goodsGroups', 'GoodsGroupsController@index')->name('admin.goodsGroups');
//$router->get('goodsGroups/create', 'GoodsGroupsController@create')->name('admin.goodsGroups.create');
//$router->get('goodsGroups/list', 'GoodsGroupsController@list')->name('admin.goodsGroups.list');
//$router->post('goodsGroups/store', 'GoodsGroupsController@store')->name('admin.goodsGroups.store');
//$router->get('goodsGroups/edit/{goodsGroup}', 'GoodsGroupsController@edit')->name('admin.goodsGroups.edit');//隐式绑定
//$router->post('goodsGroups/update/{goodsGroup}', 'GoodsGroupsController@update')->name('admin.goodsGroups.update');//隐式绑定
//$router->get('goodsGroups/destroy/{goodsGroup}', 'GoodsGroupsController@destroy')->name('admin.goodsGroups.destroy');//隐式绑定
//$router->post('goodsGroups/destroyBat', 'GoodsGroupsController@destroyBat')->name('admin.goodsGroups.destroyBat');

}
