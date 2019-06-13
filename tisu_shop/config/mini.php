<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/23
 * Time: 19:02
 */

return [
    /**
     * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
     */
    'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];