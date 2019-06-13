<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/9
 * Time: 16:13
 */

return [
    'open' => [
        'domain' => env('WLN_OPEN_DOMAIN', 'http://114.67.231.162/api'),
        'app_key' => env('WLN_OPEN_APP_KEY', '3123415742'),
        'app_secret' => env('WLN_OPEN_APP_SECRET', 'c3b5fee170b52b8397852c8ba03ef109'),
    ],
    'b2c' => [
        'domain' => env('WLN_B2C_DOMAIN', 'http://114.67.231.99/open/api'),
        'app_key' => env('WLN_B2C_APP_KEY', '19ZY0226TEST'),
        'app_secret' => env('WLN_B2C_APP_SECRET', '01A3F37CF67F3EFDA61127980B31C2B8'),
        'shop_nick' => env('WLN_B2C_SHOP_NICK', 'zhiyue')
    ],
    'stock' => [
        'map' => []
    ]

];