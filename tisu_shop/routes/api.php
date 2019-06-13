<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Routing\Router;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group([
    'middleware' => ['api', 'req.fmt'],
    'namespace' => 'Api',
    'prefix' => 'auth'
], function (Router $api) {
    $api->post('verification', 'AuthController@verification');// 短信验证码发送
//    $api->post('login', 'AuthController@login')->middleware('login.account');//API登录
    $api->post('login', 'AuthController@login');// 手机验证码登录
    $api->post('refresh', 'AuthController@refresh');//刷新token
    $api->post('me', 'AuthController@me');//个人信息
    $api->post('logout', 'AuthController@logout');//退出登录
});

// 需要 token 验证的接口
Route::group([
    'namespace' => 'Api',
    'middleware' => ['auth:api', 'req.fmt']

], function (Router $api) {
    ## 配置
    $api->post('setting/page/home', 'PageController@home');//A0302 选货

    ## 路由：Shop
    $api->post('shop/info', 'ShopController@info');//店铺详情
    $api->post('shop/update', 'ShopController@update');//更新店铺信息

    ##用户优惠券
    $api->post('buyerCoupon/list', 'BuyerCouponController@list');//用户优惠券列表

    ##路由：用户
    $api->post('user', 'UsersController@info');//用户详情
    $api->post('user/update', 'UsersController@update');//更新用户信息
    $api->post('user/sms', 'UsersController@sms');// 短信验证码发送

    //登录用户选择店铺
    $api->post('user/shopList', 'UsersController@shopList');//用户店铺列表
    $api->post('phoneChange', 'UsersController@phoneChange');//修改手机号

    ## 路由：吐槽
    $api->post('teasing/store', 'TeasingsController@store');//保存吐槽信息

    ## 路由：客户
    $api->post('buyer/list', 'BuyerController@list');//客户列表
    $api->post('buyer/info', 'BuyerController@info');//客户详情
    $api->post('buyer/update', 'BuyerController@update');//更新客户备注
    $api->post('orderList', 'BuyerController@orderList');//客户资料订单列表

    ##资产
    $api->post('turnover', 'TurnoverController@sum');//总资产
    $api->post('turnover/info', 'TurnoverController@info');// 默认本月收支明细
    $api->post('turnover/dayList', 'TurnoverController@list');// 根据日查询收支明细
    $api->post('turnover/monthList', 'TurnoverController@monthList');//根据月查询收支明细

    ## 管理员
    $api->post('manage/list', 'ShopManageController@list');//管理员列表
//    $api->post('manage/type_list', 'ShopManageController@type_list');//客户列表
    $api->post('manage/sms', 'ShopManageController@sms');//发送短信
    $api->post('manage/info', 'ShopManageController@info');//管理员详情
    $api->post('manage/store', 'ShopManageController@store');//添加管理员
    $api->post('manage/delete', 'ShopManageController@delete');//删除管理员
    $api->post('manage/update', 'ShopManageController@update');//更新管理员

    //商品查询
    Route::group([
        'prefix' => 'orgGoods',
    ], function (Router $api) {
        $api->post('list', 'OrgGoodController@list');//可选款的商品
        $api->post('detail', 'OrgGoodController@detail');//可选款的商品详情
//        $api->post('spec', 'OrgGoodController@spec');//可选款的SKU商品详情
    });
    //活动查询
    Route::group([
        'prefix' => 'event',
    ], function (Router $api) {
        $api->post('list', 'EventsController@list');//活动列表
        $api->post('detail', 'EventsController@detail');//活动详情
    });
    Route::group([
        'prefix' => 'shopEvent',
    ], function (Router $api) {
        $api->post('store', 'ShopEventController@store');//活动报名
    });
    ##活动商品
    Route::group([
        'prefix' => 'promoItem',
    ], function (Router $api) {
        $api->post('store', 'PromoItemController@store');//选择商品
        $api->post('list', 'PromoItemController@list');//商品列表
        $api->post('upper', 'PromoItemController@upper');//商品列表
    });
    //仓库商品
    Route::group([
        'prefix' => 'goods',
    ], function (Router $api) {
        $api->post('list', 'GoodsController@list');//可选款的商品
        $api->post('goodStore', 'GoodsController@goodStore');//添加到仓库
        $api->post('goodSpecStore', 'GoodsController@goodSpecStore');//添加到仓库
        $api->post('upper_lower', 'GoodsController@upper_lower');//上架下架到店铺
        $api->post('delete', 'GoodsController@delete');//删除
        $api->post('update', 'GoodsController@update');//修改商品
        $api->post('category_list', 'GoodsController@category_list');//商品分类
        $api->post('brand_list', 'GoodsController@brand_list');//商品品牌
        $api->post('detail', 'GoodsController@detail');//商品详情
//        $api->post('preview', 'GoodsController@preview');//商品预览
//        $api->post('spec', 'GoodsController@spec');//商品SKU信息（根据颜色、尺码、商品ID）

    });

    //商品图片
    Route::group([
        'prefix' => 'goods_image',
    ], function (Router $api) {
        $api->post('delete', 'GoodsImageController@delete');//删除
        $api->post('sort', 'GoodsImageController@picMove');//排序
        $api->post('is_show', 'GoodsImageController@is_show');//激活隐藏
    });

    Route::group([
        'prefix' => 'goods_spec',
    ], function (Router $api) {
        $api->post('update', 'GoodsSpecsController@update');//修改SKU信息
        $api->post('detail', 'GoodsSpecsController@detail');//SKU信息详情页
        $api->post('upper_lower', 'GoodsSpecsController@upper_lower');//SKU上架下架到店铺

    });

    ## 商品分组
    Route::group([
        'prefix' => 'goods_groups',
    ], function (Router $api) {
        $api->post('list', 'GoodsGroupsController@list');//分组列表
        $api->post('detail', 'GoodsGroupsController@detail');//分组详情
        $api->post('store', 'GoodsGroupsController@store');//添加分组
        $api->post('update', 'GoodsGroupsController@update');//修改分组名
        $api->post('delete', 'GoodsGroupsController@delete');//删除
    });

    ## 商品分组明细
    Route::group([
        'prefix' => 'goods_groups_item',
    ], function (Router $api) {
        $api->post('list', 'GoodsGroupItemController@list');//分组明细列表
        $api->post('store', 'GoodsGroupItemController@store');//添加
        $api->post('update', 'GoodsGroupItemController@update');//修改
        $api->post('delete', 'GoodsGroupItemController@delete');//删除
        $api->post('destroyBat', 'GoodsGroupItemController@destroyBat');//批量删除
    });

    // 查询订单
    Route::group([
        'prefix' => 'orders',
    ], function (Router $api) {
        $api->post('appList', 'OrderController@appList'); //状态or订单号
        $api->post('detail', 'OrderController@detail');
        $api->post('update_address', 'OrderController@update_address');
//        $api->post('update_price', 'OrderController@update_price');       //APP红人取消订单改价功能
        $api->post('cancel_order', 'OrderController@cancel_order');
    });

    ## 图片管理
    $api->post('uploader', 'UploadFileController@store');//图片上传

    ## 卡片内容管理
    Route::group([
        'prefix' => 'page_content',
    ], function (Router $api) {
        $api->post('store', 'PageContentController@store');
        $api->post('update', 'PageContentController@update');
        $api->post('list', 'PageContentController@list');
        $api->post('delete', 'PageContentController@delete');
    });

    Route::group([
        'prefix' => 'page_content_items',
    ], function (Router $api) {
        $api->post('update', 'PageContentsItemController@update');
        $api->post('delete', 'PageContentsItemController@delete');
        $api->post('list', 'PageContentsItemController@list');

    });

    ##客户数据
    Route::group([
        'prefix' => 'customer',
    ], function (Router $api) {
        $api->post('buyer/count', 'CustomerDataController@buyerCount');//当日客户总数
        $api->post('customer/data', 'CustomerDataController@customerData');//客户数据
        $api->post('count', 'CustomerDataController@count');//客户数据
    });

    Route::group([
        'prefix' => 'page_content_goods',
    ], function (Router $api) {
        $api->post('delete', 'PageContentGoodController@delete');
        $api->post('list', 'PageContentGoodController@list');
    });

    ## 维权退款订单管理
    Route::group([
        'prefix' => 'refund',
    ], function (Router $api) {
        #APP
        $api->post('appList', 'RefundsController@appList');
        $api->post('detail', 'RefundsController@detail');
        $api->post('detail_info', 'RefundsController@detail_info');
        $api->post('refund_process', 'RefundsController@refund_process');

    });

    ##消息
    Route::group([
        'prefix' => 'message',
    ], function (Router $api) {
        $api->post('index', 'MessageController@index');
        $api->post('list', 'MessageController@list');
        $api->post('update', 'MessageController@update');
    });

    ## 营销管理
    Route::group([
        'prefix' => 'promo',
    ], function (Router $api) {
//        $api->post('store', 'PromosController@store');
//        $api->post('list', 'PromosController@list');
//        $api->post('effect', 'PromosController@effect');
//        $api->post('delete', 'PromosController@delete');
    });

    ##交易数据
    Route::group([
        'prefix' => 'tradeData',
    ], function (Router $api) {
        ##交易数据
        $api->post('trade', 'TradeDataController@trade');
        ##按天记录浏览量
        $api->post('recordPageView', 'TradeDataController@recordPageView');
        ##交易数据统计
        $api->post('tradeStatistics', 'TradeDataController@tradeStatistics');
        ##营业额
        $api->post('turnover', 'TradeDataController@turnover');
    });


});
