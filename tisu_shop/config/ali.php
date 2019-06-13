<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/27
 * Time: 11:21
 */

return [

    'accessKeyId' => env('ALI_SMS_ACCESS_KEY_ID', 'LTAI11clXscDZTk1'),
    'accessSecret' => env('ALI_SMS_ACCESS_SECRET', 'y5NqdiirNTtfXanuJNjul5zyeta75o'),
    'signName' => env('ALI_SMS_SIGN_NAE', '执悦网络'),
    'templateTypes' => [
        'authentication' => ['name' => '身份验证验证码', 'code' => 'SMS_161500423', 'content' => '验证码${code}，您正在进行身份验证，打死不要告诉别人哦！'],
        'login_certification' => ['name' => '登录确认验证码', 'code' => 'SMS_161500422', 'content' => '验证码${code}，您正在登录，若非本人操作，请勿泄露。'],
        'login_abnormal' => ['name' => '登录异常验证码', 'code' => 'SMS_161500421', 'content' => '验证码${code}，您正尝试异地登录，若非本人操作，请勿泄露。'],
        'register' => ['name' => '用户注册验证码', 'code' => 'SMS_161500420', 'content' => '验证码${code}，您正在注册成为新用户，感谢您的支持！'],
        'change_password' => ['name' => '修改密码验证码', 'code' => 'SMS_161500419', 'content' => '验证码${code}，您正在尝试修改登录密码，请妥善保管账户信息。'],
        'change_info' => ['name' => '信息变更验证码', 'code' => 'SMS_161500418', 'content' => '验证码${code}，您正在尝试变更重要信息，请妥善保管账户信息。'],
    ],
];