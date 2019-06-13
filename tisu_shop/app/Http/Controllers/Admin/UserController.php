<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Ml\Response\Result;

class UserController extends BaseController
{
    //
    /**
     * 用户基本信息
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return $this->backend_view('user.edit', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @param Result $result
     * @return array
     */
    public function update(Request $request, User $user, Result $result)
    {
        if ($request->user()->cant('update', $user)) {
            return $result->failed('没有权限')->toArray();
        }

        $data = $request->only('name', 'username', 'sex', 'email', 'avatar');
        $user->update($data);

        $result->succeed($data);

        return $result->toArray();

    }

    /**
     * 页面：修改密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPasswordFormPage()
    {
        return $this->backend_view('user.password');
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
            'old_password' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.min' => '新密码至少为6位',
            'password.confirmed' => '确认密码与新密码不一致.',
        ]);

        if (!$validator->passes()) {
            $result->failed($validator->errors()->first());
            return $result->toArray();
        }

        if ($request->password == $request->old_password) {
            $result->failed('新密码不可与原密码一致！');
            return $result->toArray();
        }

        if (!$this->confirmedOldPassword($user, $request->old_password)) {
            $result->failed('原密码错误!');
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

    /**
     * 检查原密码是否正确
     *
     * @param User $user
     * @param $old_password
     * @return mixed
     */
    protected function confirmedOldPassword(User $user, $old_password)
    {
        return Hash::check($old_password, $user->password);
    }

}
