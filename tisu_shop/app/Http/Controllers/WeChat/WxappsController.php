<?php

namespace App\Http\Controllers\WeChat;

use App\Lib\Response\Result;
use App\Models\Wxapp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxappsController extends Controller
{
    //
    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function matchShop(Request $request, Result $result)
    {
        $app_id = $request->get('app_id');
        $wxapp = Wxapp::where('app_id', $app_id)->first(['app_name', 'shop_id', 'phone_no']);
        if (!$wxapp) {
            return $result->failed('未匹配到小程序信息')->toArray();
        }

        //店铺
        $wxapp->load('shop');

        if (empty($wxapp->shop)) {
            return $result->failed('未匹配到对应的店铺信息')->toArray();
        }

        $result->succeed($wxapp);

        return $result->toArray();
    }
}
