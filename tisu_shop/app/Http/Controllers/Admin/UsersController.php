<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\CreateUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Ml\Requests\Ajax\UserRequest;
use Ml\Response\Result;
use Spatie\Permission\Models\Role;

class UsersController extends BaseController
{
    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['name', 'username', 'email', 'password', 'status', 'sex', 'bool_admin', 'phone'];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return $this->backend_view('users.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = User::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
                if ($value !== '') {

                    if (strpos($field, 'name') !== false || strpos($field, 'nick') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        $query->where('user_type', '=', User::USER_TYPE_ADMIN);

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');;
        if ($per_page > 100) {
            //限制最大100
            $per_page = 100;
        }
        $query = $query->orderBy('id', 'desc');
        $query->with('roles');

        $data = $query->paginate($per_page);
//        $data->withPath($request->fullUrl());

        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(User $user)
    {
        $this->authorize('view', $user);//视图权限
        $roles = Role::get()->pluck('name', 'remarks')->toArray();
        $userRoles = [];

        return $this->backend_view('users.create_edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * 添加
     * @param CreateUpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function store(CreateUpdateRequest $request, Result $result)
    {

        if ($request->user()->cant('create', User::class)) {
            return $result->failed('没有权限')->toArray();
        }

        try {

            $data = $request->only($this->fields);

            $data['password'] = bcrypt($data['password']);
            $data['user_type'] = $data['user_type'] ?? User::USER_TYPE_ADMIN;

            $user = User::create($data);

            $roles = $request->input('roles') ? $request->input('roles') : [];
            $user->assignRole($roles);
            // 要求：User和配置的 auth.default.guard 对应的provider 使用的 user model 为同一个类
            //（因为 Role 默认的guard为配置）

            $result->succeed($user);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param User $user
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('view', $user);//视图权限

        $roles = Role::get()->pluck('name', 'remarks')->toArray();
        $userRoles = $user->roles()->pluck('name', 'name')->toArray();


        return $this->backend_view('users.create_edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * 更新
     *
     * @param CreateUpdateRequest $request
     * @param User $user
     * @param Result $result
     * @return array
     */
    public function update(CreateUpdateRequest $request, User $user, Result $result)
    {
        if ($request->user()->cant('update', $user)) {
            return $result->failed('没有权限')->toArray();
        }

        try {
            $data = $request->only($this->fields);

            $user->update($data);

            $roles = $request->input('roles') ? $request->input('roles') : [];
            $user->syncRoles($roles);

            $result->succeed($user);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param User $user
     * @param Result $result
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function destroy(User $user, Result $result, Request $request)
    {

        if ($request->user()->cant('delete', $user)) {
            return $result->failed('没有权限')->toArray();
        }

        if (!$user) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $user->delete();
            if ($del) {
                $result->succeed($user);
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
        if ($request->user()->cant('destroyBat', User::class)) {
            return $result->failed('没有权限')->toArray();
        }

        $ids = $request->get('ids');
        if ($ids && is_array($ids)) {
            $dels = User::whereIn('id', $ids)->delete();
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


    /**
     * 页面：修改密码
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPasswordFormPage(User $user)
    {
        return $this->backend_view('users.password', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @param Result $result
     * @return array
     */
    public function passwordRequest(Request $request, User $user, Result $result)
    {

        $validator = \Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.min' => '新密码至少为6位',
            'password.confirmed' => '确认密码与新密码不一致.',
        ]);

        if (!$validator->passes()) {
            $result->failed($validator->errors()->first());
            return $result->toArray();
        }

        $upd = $user->update(['password' => $request->password]);
        if ($upd) {
            $result->setMessage('密码更新成功');
        } else {
            $result->setMessage('密码更新失败');
        }

        return $result->toArray();

    }

}
