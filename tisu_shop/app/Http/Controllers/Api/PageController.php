<?php

namespace App\Http\Controllers\Api;

use App\Models\ShopManager;

class PageController extends BaseController
{
    //
    public function home()
    {
        //判断用户归属的店铺
        $user_id = auth('api')->id();
        $shop_id = ShopManager::where('user_id', '=', $user_id)->first();

        //根据店铺ID 查询出店铺对应的数据
        $page_home = config('bs.page.home');

        return $this->result->succeed(['modules' => $page_home])->toArray();
    }
}
