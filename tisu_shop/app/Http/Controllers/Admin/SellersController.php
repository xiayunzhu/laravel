<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\CreateSellerRequest;
use App\Http\Requests\Admin\User\CreateUpdateRequest;
use App\Http\Requests\Admin\User\UpdateSellerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Ml\Requests\Ajax\UserRequest;
use Ml\Response\Result;
use Spatie\Permission\Models\Role;

class SellersController extends BaseController
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
        return $this->backend_view('sellers.index');
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
        $queryFields = $request->get('queryFields') ? $request->get('queryFields') : [];

        //查询条件处理
        if ($queryFields) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false || strpos($field, 'nick') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }
        $query = $query->where('user_type', '=', User::USER_TYPE_SELLER);

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');;
        if ($per_page > 100) {
            //限制最大100
            $per_page = 100;
        }
        $query = $query->orderBy('id', 'desc');

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

        return $this->backend_view('sellers.create_edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * 添加
     * @param CreateSellerRequest $request
     * @param Result $result
     * @return array
     */
    public function store(CreateSellerRequest $request, Result $result)
    {
        try {
            $data = $request->only(['phone', 'status', 'sex']);

            $data['user_type'] = User::USER_TYPE_SELLER;
            $data['name'] = $data['phone'];
            $data['username'] = $data['phone'];

            $user = User::create($data);

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


        return $this->backend_view('sellers.create_edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * 更新
     *
     * @param UpdateSellerRequest $request
     * @param User $user
     * @param Result $result
     * @return array
     */
    public function update(UpdateSellerRequest $request, User $user, Result $result)
    {
        try {
            if ($request->user()->cant('update', $user)) {
                return $result->failed('没有权限')->toArray();
            }
            $data = $request->only($this->fields);
            $user->update($data);

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
        return $this->backend_view('sellers.password', compact('user'));
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
