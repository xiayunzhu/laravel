<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Ml\Response\Result;
use Spatie\Permission\Models\Permission;

class PermissionsController extends BaseController
{
    /**
     * 接收的字段
     * @var array
     */
    private $fields = ['name', 'remarks'];

    /**
     * 列表
     *
     * @param Request $request
     * @param Permission $permission
     * @return mixed
     */
    public function index(Request $request, Permission $permission)
    {

        return $this->backend_view('permissions.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Permission::query();

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');
        if ($per_page > 100) {
            //限制最大100
            $per_page = 100;
        }
        $query = $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Permission $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Permission $permission)
    {
        return $this->backend_view('permissions.create_edit', compact('permission'));
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
            $permission = Permission::create($request->only($this->fields));
            $result->succeed($permission);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Permission $permission
     * @return mixed
     */
    public function edit(Permission $permission)
    {
//        $permission = Permission::find($id);
//        $this->authorize('update', $permission);
        return $this->backend_view('permissions.create_edit', compact('permission'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Permission $permission
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Permission $permission, Result $result)
    {
//        $permission = Permission::find($id);

        $permission->fill($request->only($this->fields));
        $upd = $permission->save();
        if ($upd)
            $result->succeed($permission);
        else
            $result->failed('更新失败');

        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Permission $permission
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Permission $permission, Result $result)
    {

//        $permission = Permission::find($id);
//        if (\request()->user()->cant('delete', $permission)) {
//            return $result->failed('没有权限')->toArray();
//        }

        if (!$permission) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $permission->delete();
            if ($del) {
                $result->succeed($permission);
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
//        if (\request()->user()->cant('destroyBat', Permission::class)) {
//            return $result->failed('没有权限')->toArray();
//        }

        $ids = $request->get('ids');
        if ($ids && is_array($ids)) {
            $dels = Permission::whereIn('id', $ids)->delete();
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

}
