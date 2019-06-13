<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/28
 * Time: 17:20
 */

return [
    /**
     * 订单生命周期:即过时关闭
     * 单位:秒
     */
    'stock' => [
        'ttl' => env('ORDER_RETURN_STOCK_TTL', 900),//15分钟
    ],
    'wait_pay' => [
        'ttl' => env('ORDER_WAI_PAY_TTL', 259200),//72小时
    ],
    'refund_address' => [
        'receiver' => '缇苏收货人',
        'mobile' => '15040219214',
        'province' => '浙江省',
        'city' => '杭州市',
        'district' => '江干区',
        'detail' => '华铁二号楼',
        'zip_code' => '101111',
    ],
];