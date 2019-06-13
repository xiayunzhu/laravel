<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Ml\Response\Result;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends BaseController
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
     * @param Role $role
     * @return mixed
     */
    public function index(Request $request, Role $role)
    {
        return $this->backend_view('roles.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Role::query();

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
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Role $role)
    {
        $permissions = Permission::get()->pluck('name', 'remarks');
        $rolePermissions = [];

        return $this->backend_view('roles.create_edit', compact('role', 'permissions', 'rolePermissions'));
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

            $role = Role::create($request->only($this->fields));
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->givePermissionTo($permissions);

            $result->succeed($role);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Role $role
     * @return mixed
     */
    public function edit(Role $role)
    {
//        $role = Role::find($id);

        $permissions = Permission::get()->pluck('name', 'remarks')->toArray();
        $rolePermissions = $role->permissions()->pluck('name', 'name')->toArray();

        return $this->backend_view('roles.create_edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Role $role
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Role $role, Result $result)
    {
        $role->update($request->only($this->fields));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);

        $result->succeed($role);

        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Role $role
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Role $role, Result $result)
    {

        if (!$role) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $role->delete();
            if ($del) {
                $result->succeed($role);
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
            $dels = Role::whereIn('id', $ids)->delete();
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
