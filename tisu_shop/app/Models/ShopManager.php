<?php

namespace App\Models;

use App\Exceptions\ShopManageException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Ml\Response\Result;

class ShopManager extends BaseModel
{
    //
    use SoftDeletes;
    protected $fillable = ['shop_id', 'user_id', 'name','type', 'status', 'remark'];

    const TYPE_SENIOR = 'senior';
    const TYPE_GENERAL = 'general';
    const TYPE_SERVICE = 'service';
    public static $typeMap = [
        self::TYPE_SENIOR => '高级管理员',
        self::TYPE_GENERAL => '普通管理员',
        self::TYPE_SERVICE => '客服人员',
    ];

    const STATUS_N = 'N';
    const STATUS_Y = 'Y';
    public static $statusMap = [
        self::STATUS_N => '停用',
        self::STATUS_Y => '启用',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function shops()
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    public function store(Request $request)
    {

        try {
            $res = User::where('phone', $request->get('phone'))->first(['id', 'name']);
            $data['shop_id'] = $request->get('shop_id');
            $data['type'] = ShopManager::TYPE_GENERAL;
            $data['status'] = ShopManager::STATUS_Y;
            if ($res) {
                $manage_id = ShopManager::where(['user_id' => $res->id, 'shop_id' => $request->get('shop_id')])->value('id');
                if (!empty($manage_id)) {
                    throw new ShopManageException('该用户已是你的管理员');
                }
                $data['user_id'] = $res->id;
                $data['remark'] = $res->id ? $res->id : $request->get('phone');
                $data['name'] = $res->id ? $res->id : $request->get('phone');
            } else {
                $param['phone'] = $request->get('phone');
                $param['name'] = $request->get('phone');
                $user = User::create($param);
                $data['user_id'] = $user->id;
                $data['remark'] = $user->name;
                $data['name'] = $user->name;
            }
            $shopManage = ShopManager::create($data);
            return $shopManage;
        } catch (\Exception $exception) {
            throw new ShopManageException($exception->getMessage());

        }
    }
}
