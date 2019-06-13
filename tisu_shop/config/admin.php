<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-27
 * Time: 15:16
 */

return [
    // 后台的 URI
    'uri' => 'admin',//administrator

    // 后台专属域名，没有的话可以留空
//    'domain' => '',

    'paginate' => [
        'limit' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => 'admin',

        'namespace' => 'App\\Http\\Controllers\\Admin',

        'middleware' => ['web', 'auth'],//admin
    ],

    /*
    |--------------------------------------------------------------------------
    | Ml-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Admin'),


    /*
    |--------------------------------------------------------------------------
    | 路由存储目录
    |--------------------------------------------------------------------------
    */
    'dir_route' => base_path('routes'),

    /*
   |--------------------------------------------------------------------------
   | 控制器存储目录
   |--------------------------------------------------------------------------
   */
    'dir_controller' => app_path('Http/Controllers/Admin'),


    /*
   |--------------------------------------------------------------------------
   | Laravel-admin database settings
   |--------------------------------------------------------------------------
   |
   | Here are database settings for laravel-admin builtin model & tables.
   |
   */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'users',
        'users_model' => \App\Models\User::class,

        // Role table and model.
        'roles_table' => 'roles',
        'roles_model' => \Spatie\Permission\Models\Role::class,

        // Permission table and model.
        'permissions_table' => 'permissions',
        'permissions_model' => \Spatie\Permission\Models\Permission::class,

    ],


    //顶部导航栏
    'menu_top' => [
        [
            "id" => "dashboard",
            "text" => "仪表盘",
            "permission" => function () {
                return true;
            },
            "icon" => "layui-icon layui-icon-console",
            "route" => "admin.dashboard",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
        [
            "id" => "system",
            "text" => "系统设置",
            "permission" => function () {
                return Auth::user()->can('manage_system');
            },
            "icon" => "layui-icon layui-icon-set",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "system.users",
                    "text" => "用户管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_users');
                    },
                    "icon" => "layui-icon layui-icon-user",
                    "route" => "admin.users",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "system.permissions",
                    "text" => "权限管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_permissions');
                    },
                    "icon" => "layui-icon layui-icon-auz",
                    "route" => "admin.permissions",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "system.roles",
                    "text" => "角色管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_roles');
                    },
                    "icon" => "layui-icon layui-icon-group",
                    "route" => "admin.roles",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
            ],
        ],
        [
            "id" => "base.info",
            "text" => "基础资料",
            "permission" => function () {
                return Auth::user()->can('manage_menu_base_info');
            },
            "icon" => "layui-icon layui-icon-set",
            "route" => "admin.base.info",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "regions.info",
                    "text" => "区域管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_region_info');
                    },
                    "icon" => "iconfont iconquyuguanli",
                    "route" => "admin.regions",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "orgGoods.info",
                    "text" => "商品分类",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_orgGood_info');
                    },
                    "icon" => "iconfont iconshangpinfenlei",
                    "route" => "admin.categories",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "orgGoods.brand",
                    "text" => "属性规格",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconshuxing1",
                    "route" => "admin.specs",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "orgGoods.spec",
                    "text" => "规格值",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconshuxing",
                    "route" => "admin.specValues",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "deliveries",
                    "text" => "运费模板",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconyunfeimobanguanli",
                    "route" => "admin.deliveries",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "brands",
                    "text" => "品牌管理",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconpinpai",
                    "route" => "admin.brands",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "uploadFiles",
                    "text" => "图片管理",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "layui-icon layui-icon-console",
                    "route" => "admin.uploadFiles",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "system.users",
                    "text" => "基础商品",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconshangpin",
                    "route" => "admin.products",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "system.permissions",
                    "text" => "仓库库存",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconkucunliang",
                    "route" => "admin.stocks",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
            ],
        ],
        [
            "id" => "base.info",
            "text" => "商城管理",
            "permission" => function () {
                return true;
            },
            "icon" => "layui-icon layui-icon-set",
            "route" => "admin.base.info",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "sellers",
                    "text" => "卖家管理",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconmaijia",
                    "route" => "admin.sellers",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "teasings",
                    "text" => "吐槽管理",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconmaijia",
                    "route" => "admin.teasings",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "shops.info",
                    "text" => "店铺资料",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_shop_info');
                    },
                    "icon" => "iconfont icondianpu",
                    "route" => "admin.shops",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ], [
                    "id" => "wxapps.info",
                    "text" => "小程序",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_region_info');
                    },
                    "icon" => "iconfont iconxiaochengxu",
                    "route" => "admin.wxapps",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],

            ],
        ],
        [
            "id" => "product",
            "text" => "商品库",
            "permission" => function () {
                return true;
            },
            "icon" => "iconfont iconshangpinku",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "orgGoods.info",
                    "text" => "原始商品",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_orgGood_info');
                    },
                    "icon" => "iconfont iconshangpin",
                    "route" => "admin.orgGoods",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "goods",
                    "text" => "售卖商品",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconshangpin",
                    "route" => "admin.goods",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ]

            ],
        ],
        [
            "id" => "buyers",
            "text" => "买家管理",
            "permission" => function () {
                return true;
            },
            "icon" => "iconfont iconmaijiaguanli",
            "route" => "",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "buyerAddresses",
                    "text" => "买家列表",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "layui-icon layui-icon-table",
                    "route" => "admin.buyers",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "favorites",
                    "text" => "买家收藏",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconshoucang",
                    "route" => "admin.favorites",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ],
                [
                    "id" => "buyerAddresses",
                    "text" => "收货地址",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont iconquyuguanli",
                    "route" => "admin.buyerAddresses",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                    "children" => [],
                ]
            ],
        ],
        [
            "id" => "orders",
            "text" => "订单管理",
            "permission" => function () {
                return true;
            },
            "icon" => "iconfont icondingdanguanli-",
            "route" => "",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "orders",
                    "text" => "订单列表",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont icondingdanguanli-",
                    "route" => "admin.orders",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "refunds",
                    "text" => "售后订单",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont icondingdanguanli-",
                    "route" => "admin.refunds",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "wxPayReports",
                    "text" => "支付报告",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "layui-icon layui-icon-console",
                    "route" => "admin.wxPayReports",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "payNotifies",
                    "text" => "支付结果",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont icondingdanguanli-",
                    "route" => "admin.payNotifies",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "payRefunds",
                    "text" => "退款记录",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "iconfont icondingdanguanli-",
                    "route" => "admin.payRefunds",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ]
            ],
        ],
        [
            "id" => "other",
            "text" => "其他功能",
            "permission" => function () {
                return Auth::user()->can('manage_menu_other');
            },
            "icon" => "layui-icon layui-icon-util",
            "route" => "admin.logs",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "files",
                    "text" => "文件管理",
                    "permission" => function () {
                        return true;
                    },
                    "icon" => "layui-icon layui-icon-file",
                    "route" => "admin.files",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "logs",
                    "text" => "系统日志",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_logs');
                    },
                    "icon" => "layui-icon layui-icon-file",
                    "route" => "admin.logs",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "loginLogs",
                    "text" => "登录日志",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_logs');
                    },
                    "icon" => "layui-icon layui-icon-file",
                    "route" => "admin.loginLogs",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
                [
                    "id" => "loginLogs",
                    "text" => "后台日志",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_logs');
                    },
                    "icon" => "layui-icon layui-icon-file",
                    "route" => "admin.operationLogs",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ],
            ],
        ],
    ],

    //左侧导航栏
    'menu_left' => [

    ],
];