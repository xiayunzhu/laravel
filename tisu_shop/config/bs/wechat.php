<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/2
 * Time: 12:19
 */
return [

    /**
     * 小程序
     */
    'mini_program' => [
        'default' => [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID', ''),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET', ''),
            'token' => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
            'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        ],
    ],

    /**
     * 微信支付
     */
    'payment' => [
        'default' => [
            'sandbox' => env('WECHAT_PAYMENT_SANDBOX', false),//沙箱测试 https://blog.csdn.net/qianfeng_dashuju/article/details/84067817
//            'app_id' => env('WECHAT_PAYMENT_APPID', ''),
//            'mch_id' => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
//            'key' => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
            'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', storage_path('pay_cert') . '/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
            'key_path' => env('WECHAT_PAYMENT_KEY_PATH', storage_path('pay_cert') . '/apiclient_key.pem'),      // XXX: 绝对路径！！！！
            'notify_url' => config('WECHAT_PAYMENT_NOTIFY_URL', 'https://dg.eziyan.top/api/wx/pay/notify'),                           // 默认支付结果通知地址
        ],
        // ...
    ],
    /**
     * 微信端-交易状态图片
     */
    'images' => [
        'order' => [
            'accomplished' => ['file_name' => 'accomplished交易完成.png', 'url' => 'https://tisu.oss-cn-hangzhou.aliyuncs.com/images/order/accomplished%E4%BA%A4%E6%98%93%E5%AE%8C%E6%88%90.png'],
            'processed' => ['file_name' => 'processed待发货.png', 'url' => 'https://tisu.oss-cn-hangzhou.aliyuncs.com/images/order/processed%E5%BE%85%E5%8F%91%E8%B4%A7.png'],
            'shipped' => ['file_name' => 'shipped运输中.png', 'url' => 'https://tisu.oss-cn-hangzhou.aliyuncs.com/images/order/shipped%E8%BF%90%E8%BE%93%E4%B8%AD.png'],
            'unpaid' => ['file_name' => 'unpaid待支付.png', 'url' => 'https://tisu.oss-cn-hangzhou.aliyuncs.com/images/order/unpaid%E5%BE%85%E6%94%AF%E4%BB%98.png'],
            'unreceived' => ['file_name' => 'unreceived待收货.png', 'url' => 'https://tisu.oss-cn-hangzhou.aliyuncs.com/images/order/unreceived%E5%BE%85%E6%94%B6%E8%B4%A7.png']
        ]
    ]
];
