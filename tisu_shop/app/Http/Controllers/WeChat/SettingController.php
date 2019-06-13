<?php

namespace App\Http\Controllers\WeChat;

use App\Lib\Response\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    //
    public function setting(Result $result)
    {
        $setting = [
            'order' => [
                'ttl' => config('bs.order.wait_pay.ttl'),//未付款订单多少时间后自动关闭
                'images' => config('bs.wechat.images.order'),//订单状态标识图片
            ],
        ];

        $result->succeed($setting);

        return $result->toArray();
    }
}
