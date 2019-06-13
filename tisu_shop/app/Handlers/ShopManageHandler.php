<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/12
 * Time: 18:11
 */

namespace App\Handlers;


use App\Exceptions\ShopManageException;
use App\Models\ShopManager;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShopManageHandler
{
    private $fields = ['name', 'type'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = ShopManager::query();
        $queryFields = $request->all();
        $user_id = \auth('api')->id();
        $shop_id = $request->get('shop_id');
        $type = ShopManager::where(['user_id' => $user_id, 'shop_id' => $shop_id])->value('type');
        //查询条件处理
        if ($queryFields) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        if ($type == ShopManager::TYPE_SERVICE) {
            $querys = $query->whereIn('type', [ShopManager::TYPE_SERVICE]);
        } elseif ($type == ShopManager::TYPE_GENERAL) {
            $querys = $query->whereIn('type', [ShopManager::TYPE_SERVICE, ShopManager::TYPE_GENERAL]);
        } else {
            $querys = $query;
        }
        $query->orderBy('id', 'desc');
        $data = $querys->paginate($per_page);
        $data->load('user');
        return $data;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws ShopManageException
     */
    public function permission(Request $request)
    {
        $manage_id = $request->get('manage_id');
        $type = ShopManager::where('id', $manage_id)->value('type');
        $user_id = \auth('api')->id();
        $user_type = ShopManager::where('user_id', $user_id)->value('type');
        if (empty($type)) {
            throw new ShopManageException('该管理员不存在');
        }
        if (($type == $user_type) || empty($user_type)) {
            throw new ShopManageException('您没有此权限');
        } else {
            return true;
        }


    }

    /**
     * @param int $id
     * @return mixed
     * @throws ShopManageException
     */
    public function delete(int $id)
    {
        $shopManage = ShopManager::find($id);
        if ($shopManage) {
            $shopManage->delete();
        } else {
            throw new ShopManageException('已删除');
        }

        return $shopManage;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ShopManageException
     */
    public function update(Request $request)
    {
        $id = $request->get('manage_id');
        $shopManage = ShopManager::find($id);
        if ($shopManage) {
            $shopManage->update($request->only($this->fields));
            return $shopManage;
        } else {
            throw new ShopManageException('管理员不存在');
        }

    }

    /**
     * @param Request $request
     * @param UserHandler $userHandler
     * @param $manages
     * @return mixed
     * @throws ShopManageException
     */
    public function store(Request $request, UserHandler $userHandler, &$manages)
    {

        try {
            $res = User::where('phone', $request->get('phone'))->first(['id', 'name']);
            $data['shop_id'] = $request->get('shop_id');
            $data['type'] = $manages['type'];
            $data['status'] = $manages['status'];
            if ($res) {
                $manage_id = ShopManager::where(['user_id' => $res->id, 'shop_id' => $request->get('shop_id')])->value('id');
                if (!empty($manage_id)) {
                    throw new ShopManageException('该用户已是你的管理员');
                }
                $data['user_id'] = $res->id;
                $data['name'] = $res->name ? $res->name : $request->get('phone');
                $shopManage = ShopManager::create($data);
            } else {
                $shopManage = DB::transaction(function () use (&$data, $request, $userHandler) {
                    $phone = $request->get('phone');
                    $user = $userHandler->store($phone);
                    $data['user_id'] = $user['id'];
                    $data['name'] = $user['name'];
                    $shopManage = ShopManager::create($data);
                    return $shopManage;
                }, 1);

            }
            return $shopManage;
        } catch (\Exception $exception) {
            throw new ShopManageException($exception->getMessage());

        }
    }

    public function create(int $shop_id, int $user_id)
    {
        $res = User::where('id', $user_id)->first(['id', 'name']);
        $data['user_id'] = $res->id;
        $data['name'] = $res->name;
        $data['type'] = ShopManager::TYPE_SENIOR;
        $data['status'] = ShopManager::STATUS_Y;
        $data['shop_id'] = $shop_id;
        $manage = ShopManager::create($data);
        return $manage;
    }
}