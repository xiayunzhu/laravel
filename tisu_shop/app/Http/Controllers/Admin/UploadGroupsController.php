<?php

namespace App\Http\Controllers\Admin;

use App\Models\UploadGroup;
use Illuminate\Http\Request;
use Ml\Response\Result;

class UploadGroupsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ["id","group_type","group_name","sort","created_at","updated_at"];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","group_type"=>"分组类型","group_name"=>"分组名称","sort"=>"排序","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param UploadGroup $uploadGroup
     * @return mixed
     */
    public function index(Request $request, UploadGroup $uploadGroup)
    {
        return $this->backend_view('uploadGroups.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = UploadGroup::query();

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
     * 创建分组的窗口页面
     * @param UploadGroup $uploadGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createWindow(UploadGroup $uploadGroup)
    {
        return $this->backend_view('uploadGroups.create_edit_window', compact('uploadGroup'));
    }
    /**
     * 修改分组的窗口页面
     * @param UploadGroup $uploadGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editWindow(UploadGroup $uploadGroup)
    {
        return $this->backend_view('uploadGroups.create_edit_window', compact('uploadGroup'));
    }

    /**
     * 新增页面
     * @param UploadGroup $uploadGroup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(UploadGroup $uploadGroup)
    {

        return $this->backend_view('uploadGroups.create_edit', compact('uploadGroup'));
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
            $data = $request->only($this->fields);
            $data['group_type'] = UploadGroup::GROUP_TYPE_OPERATING;
            $model = UploadGroup::create($data);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param UploadGroup $uploadGroup
     * @return mixed
     */
    public function edit(UploadGroup $uploadGroup)
    {

        return $this->backend_view('uploadGroups.create_edit', compact('uploadGroup'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param UploadGroup $uploadGroup
     * @param Result $result
     * @return array
     */
    public function update(Request $request, UploadGroup $uploadGroup, Result $result)
    {
        try {
            $data = $request->only($this->fields);
            $data['group_type'] = UploadGroup::GROUP_TYPE_OPERATING;
            $uploadGroup->update($data);
            $result->succeed($uploadGroup);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param UploadGroup $uploadGroup
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(UploadGroup $uploadGroup, Result $result)
    {
        if (!$uploadGroup) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $uploadGroup->delete();
            if ($del) {
                $result->succeed($uploadGroup);
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
            $dels = UploadGroup::whereIn('id', $ids)->delete();
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

//## 路由：UploadGroup
//$router->get('uploadGroups', 'UploadGroupsController@index')->name('admin.uploadGroups');
//$router->get('uploadGroups/create', 'UploadGroupsController@create')->name('admin.uploadGroups.create');
//$router->get('uploadGroups/list', 'UploadGroupsController@list')->name('admin.uploadGroups.list');
//$router->post('uploadGroups/store', 'UploadGroupsController@store')->name('admin.uploadGroups.store');
//$router->get('uploadGroups/edit/{uploadGroup}', 'UploadGroupsController@edit')->name('admin.uploadGroups.edit');//隐式绑定
//$router->post('uploadGroups/update/{uploadGroup}', 'UploadGroupsController@update')->name('admin.uploadGroups.update');//隐式绑定
//$router->get('uploadGroups/destroy/{uploadGroup}', 'UploadGroupsController@destroy')->name('admin.uploadGroups.destroy');//隐式绑定
//$router->post('uploadGroups/destroyBat', 'UploadGroupsController@destroyBat')->name('admin.uploadGroups.destroyBat');

}
