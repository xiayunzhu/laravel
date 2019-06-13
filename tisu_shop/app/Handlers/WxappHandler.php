<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/23
 * Time: 19:26
 */

namespace App\Handlers;


use App\Exceptions\WxappException;
use App\Models\Wxapp;

class WxappHandler
{

    /**
     * @param $app_id
     * @return mixed
     * @throws WxappException
     */
    public function getByAppId($app_id)
    {
//        $count = Wxapp::where('app_id', $app_id)->count();
//        if ($count > 1) {
//            throw new WxappException('小程序注册异常[重复注册]', 101001);
//        }

        $wxapp = Wxapp::where('app_id', $app_id)->first();
        if ($wxapp) {
            return $wxapp;
        }

        throw new WxappException('小程序未在后台注册', 101001);
    }
}