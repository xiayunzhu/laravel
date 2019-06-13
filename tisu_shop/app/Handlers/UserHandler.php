<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 10:58
 */

namespace App\Handlers;


use App\Exceptions\UserException;
use App\Models\User;
use Illuminate\Http\Request;

class UserHandler
{
    private $fields = ['name', 'username', 'qq_code', 'wx_code'];

    /**
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paging(int $user_id, Request $request)
    {

        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $shops = User::with(['shopManage', 'shopManage.shops'])->where('id', $user_id)->orderby('id', 'desc')->paginate($per_page);
        return $shops;
    }

    /**
     * @param int $user_id
     * @param Request $request
     * @return mixed
     * @throws UserException
     */
    public function update(int $user_id, Request $request)
    {
        $user = User::find($user_id);
        if ($user) {
            $user->update($request->only($this->fields));
        } else {
            throw new UserException('请重新登录');
        }

        return $user;
    }

    /**
     * @param int $phone
     * @return User
     */
    public function store(int $phone)
    {
        $data['name'] = $phone;
        $data['phone'] = $phone;
        $data['user_type'] = User::USER_TYPE_SELLER;
        $user = User::create($data);
        return $user;
    }
}