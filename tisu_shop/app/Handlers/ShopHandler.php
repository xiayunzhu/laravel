<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 10:35
 */

namespace App\Handlers;


use App\Exceptions\ShopException;
use App\Models\Buyer;
use App\Models\CustomerData;
use App\Models\Shop;
use App\Models\ShopManager;
use Illuminate\Http\Request;

class ShopHandler
{
    private $fields = ['shop_nick', 'shop_name', 'icon_url', 'introduction', 'template'];
    private $shopId;


    /**
     * @param int $shopId
     * @return bool
     * @throws ShopException
     */
    public function permission(int $shopId)
    {
        $user_id = \auth('api')->id();
        $manage_id = ShopManager::where(['shop_id' => $shopId, 'user_id' => $user_id])->value('id');
        if ($manage_id) {
            return true;
        } else {
            throw new ShopException('您没有此权限');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ShopException
     */
    public function update(Request $request)
    {
        $id = $request->get('shop_id');
        $shop = Shop::find($id);
        if ($shop) {
            $shop->update($request->only($this->fields));
        } else {
            throw new ShopException('商店不存在');
        }

        return $shop;
    }

    /**
     * @param $user_id
     * @param Request $request
     * @return mixed
     * @throws ShopException
     */
    public function shopList($user_id, Request $request)
    {
        $shop_ids = ShopManager::where('user_id', $user_id)->get()->pluck('shop_id');
        if(empty($shop_ids)){
            throw new ShopException('您没有管理的店铺');
        }
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $shop = Shop::whereIn('id', $shop_ids)->orderby('id', 'desc')->paginate($per_page);
        return $shop;
    }

}