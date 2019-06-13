<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-27
 * Time: 15:14
 */


/*
 * -------------------------------------------------------------------------
 * 后台不需要需要认证相关路由
 * -------------------------------------------------------------------------
 */

use Illuminate\Routing\Router;

//use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => ['web']
], function (Router $router) {

    # 登录页面
    $router->get('login', 'LoginController@showLoginForm')->name('admin.login');

    # 登录request
    $router->post('login', 'LoginController@login')->middleware('login.account');

    # 退出
    $router->get('logout', 'LoginController@logout')->name('admin.logout');

    # 无权限提示
    $router->get('permission-denied', 'WelcomeController@permissionDenied')->name('admin.permission-denied');

    # 文件上传相关路由
    Route::post('uploader', 'UploadController@uploader')->name('uploader');


});


/*
 * -------------------------------------------------------------------------
 * 后台需要认证相关路由
 * -------------------------------------------------------------------------
 */
Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    # 首页
    $router->get('/', 'WelcomeController@dashboard')->name('admin.dashboard');

    # 用户
    $router->get('users', 'UsersController@index')->name('admin.users');
    $router->get('users/create', 'UsersController@create')->name('admin.users.create');
    $router->get('users/list', 'UsersController@list')->name('admin.users.list');
    $router->post('users/store', 'UsersController@store')->name('admin.users.store');
    $router->get('users/edit/{user}', 'UsersController@edit')->name('admin.users.edit');//隐式绑定
    $router->post('users/update/{user}', 'UsersController@update')->name('admin.users.update');//隐式绑定
    $router->get('users/destroy/{user}', 'UsersController@destroy')->name('admin.users.destroy');//隐式绑定
    $router->post('users/destroyBat', 'UsersController@destroyBat')->name('admin.users.destroyBat');
    $router->get('users/password/{user}', 'UsersController@showPasswordFormPage')->name('admin.users.password.edit');//隐式绑定
    $router->post('users/password/{user}', 'UsersController@passwordRequest')->name('admin.users.password.update');//隐式绑定


    ## 个人设置
    $router->get('user/{user}/edit', 'UserController@edit')->name('user.edit');
    $router->post('user/{user}', 'UserController@update')->name('user.update');
    $router->get('user/{user}/psd', 'UserController@showPasswordFormPage')->name('user.password.edit');
    $router->post('user/psd/{user}', 'UserController@passwordRequest')->name('user.password.update');

    ## 权限
    $router->get('permissions', 'PermissionsController@index')->name('admin.permissions');
    $router->get('permissions/list', 'PermissionsController@list')->name('admin.permissions.list');
    $router->get('permissions/create', 'PermissionsController@create')->name('admin.permissions.create');
    $router->post('permissions/store', 'PermissionsController@store')->name('admin.permissions.store');
    $router->get('permissions/edit/{permission}', 'PermissionsController@edit')->name('admin.permissions.edit');
    $router->post('permissions/update/{permission}', 'PermissionsController@update')->name('admin.permissions.update');
    $router->get('permissions/destroy/{permission}', 'PermissionsController@destroy')->name('admin.permissions.destroy');
    $router->post('permissions/destroyBat', 'PermissionsController@destroyBat')->name('admin.permissions.destroyBat');

    ## 角色
    $router->get('roles', 'RolesController@index')->name('admin.roles');
    $router->get('roles/list', 'RolesController@list')->name('admin.roles.list');
    $router->get('roles/create', 'RolesController@create')->name('admin.roles.create');
    $router->post('roles/store', 'RolesController@store')->name('admin.roles.store');
    $router->get('roles/edit/{role}', 'RolesController@edit')->name('admin.roles.edit');
    $router->post('roles/update/{role}', 'RolesController@update')->name('admin.roles.update');
    $router->get('roles/destroy/{role}', 'RolesController@destroy')->name('admin.roles.destroy');
    $router->post('roles/destroyBat', 'RolesController@destroyBat')->name('admin.roles.destroyBat');


    ## 系统日志
    ## rap2hpoutre/laravel-log-viewer
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('admin.logs');

    //## 路由：{model}
    $router->get('files', 'FilesController@index')->name('admin.files');
    $router->get('files/create', 'FilesController@create')->name('admin.files.create');
    $router->get('files/list', 'FilesController@list')->name('admin.files.list');
    $router->post('files/store', 'FilesController@store')->name('admin.files.store');
    $router->get('files/edit/{file}', 'FilesController@edit')->name('admin.files.edit');//隐式绑定
    $router->post('files/update/{file}', 'FilesController@update')->name('admin.files.update');//隐式绑定
    $router->get('files/destroy/{file}', 'FilesController@destroy')->name('admin.files.destroy');//隐式绑定
    $router->post('files/destroyBat', 'FilesController@destroyBat')->name('admin.files.destroyBat');

    ## 路由：Shop店铺资料
    $router->get('shops', 'ShopsController@index')->name('admin.shops');
    $router->get('shops/create', 'ShopsController@create')->name('admin.shops.create');
    $router->get('shops/list', 'ShopsController@list')->name('admin.shops.list');
    $router->post('shops/store', 'ShopsController@store')->name('admin.shops.store');
    $router->get('shops/edit/{shop}', 'ShopsController@edit')->name('admin.shops.edit');//隐式绑定
    $router->post('shops/update/{shop}', 'ShopsController@update')->name('admin.shops.update');//隐式绑定
    $router->get('shops/destroy/{shop}', 'ShopsController@destroy')->name('admin.shops.destroy');//隐式绑定
    $router->post('shops/destroyBat', 'ShopsController@destroyBat')->name('admin.shops.destroyBat');

    ## 路由：Region区域管理
    $router->get('regions', 'RegionsController@index')->name('admin.regions');
    $router->get('regions/create', 'RegionsController@create')->name('admin.regions.create');
    $router->get('regions/list', 'RegionsController@list')->name('admin.regions.list');
    $router->post('regions/store', 'RegionsController@store')->name('admin.regions.store');
    $router->get('regions/edit/{region}', 'RegionsController@edit')->name('admin.regions.edit');//隐式绑定
    $router->post('regions/update/{region}', 'RegionsController@update')->name('admin.regions.update');//隐式绑定
    $router->get('regions/destroy/{region}', 'RegionsController@destroy')->name('admin.regions.destroy');//隐式绑定
    $router->post('regions/destroyBat', 'RegionsController@destroyBat')->name('admin.regions.destroyBat');
    $router->get('regions/addressWindow', 'RegionsController@addressWindow')->name('admin.regions.addressWindow');


    ## 路由：Wxapp 小程序配置
    $router->get('wxapps', 'WxappsController@index')->name('admin.wxapps');
    $router->get('wxapps/create', 'WxappsController@create')->name('admin.wxapps.create');
    $router->get('wxapps/list', 'WxappsController@list')->name('admin.wxapps.list');
    $router->post('wxapps/store', 'WxappsController@store')->name('admin.wxapps.store');
    $router->get('wxapps/edit/{wxapp}', 'WxappsController@edit')->name('admin.wxapps.edit');//隐式绑定
    $router->post('wxapps/update/{wxapp}', 'WxappsController@update')->name('admin.wxapps.update');//隐式绑定
    $router->get('wxapps/destroy/{wxapp}', 'WxappsController@destroy')->name('admin.wxapps.destroy');//隐式绑定
    $router->post('wxapps/destroyBat', 'WxappsController@destroyBat')->name('admin.wxapps.destroyBat');

    ## 路由：OrgGood 原始商品
    $router->get('orgGoods', 'OrgGoodsController@index')->name('admin.orgGoods');
    $router->get('orgGoods/create', 'OrgGoodsController@create')->name('admin.orgGoods.create');
    $router->get('orgGoods/list', 'OrgGoodsController@list')->name('admin.orgGoods.list');
    $router->post('orgGoods/store', 'OrgGoodsController@store')->name('admin.orgGoods.store');
    $router->get('orgGoods/edit/{orgGood}', 'OrgGoodsController@edit')->name('admin.orgGoods.edit');//隐式绑定
    $router->post('orgGoods/update/{orgGood}', 'OrgGoodsController@update')->name('admin.orgGoods.update');//隐式绑定
    $router->get('orgGoods/destroy/{orgGood}', 'OrgGoodsController@destroy')->name('admin.orgGoods.destroy');//隐式绑定
    $router->post('orgGoods/destroyBat', 'OrgGoodsController@destroyBat')->name('admin.orgGoods.destroyBat');
    $router->post('orgGoods/specs', 'OrgGoodsController@specs')->name('admin.orgGoods.specs');
    $router->post('orgGoods/upVersion/{orgGood}', 'OrgGoodsController@upVersion')->name('admin.orgGoods.upVersion');//隐式绑定
    $router->get('orgGoods/erpWindow', 'OrgGoodsController@erpWindow')->name('admin.orgGoods.erpWindow');
    ## 路由：Teasing
    $router->get('teasings', 'TeasingController@index')->name('admin.teasings');
    $router->get('teasings/detail', 'TeasingController@detail')->name('admin.teasings.detail');
    $router->get('teasings/create', 'TeasingController@create')->name('admin.teasings.create');
    $router->get('teasings/list', 'TeasingController@list')->name('admin.teasings.list');
    $router->post('teasings/store', 'TeasingController@store')->name('admin.teasings.store');
    $router->get('teasings/edit/{teasing}', 'TeasingController@edit')->name('admin.teasings.edit');//隐式绑定
    $router->post('teasings/update/{teasing}', 'TeasingController@update')->name('admin.teasings.update');//隐式绑定
    $router->get('teasings/destroy/{teasing}', 'TeasingController@destroy')->name('admin.teasings.destroy');//隐式绑定
    $router->post('teasings/destroyBat', 'TeasingController@destroyBat')->name('admin.teasings.destroyBat');

    ## 路由：OrgGoodsSpec 商品规格
    $router->get('orgGoodsSpecs', 'OrgGoodsSpecsController@index')->name('admin.orgGoodsSpecs');
    $router->get('orgGoodsSpecs/create', 'OrgGoodsSpecsController@create')->name('admin.orgGoodsSpecs.create');
    $router->get('orgGoodsSpecs/list', 'OrgGoodsSpecsController@list')->name('admin.orgGoodsSpecs.list');
    $router->post('orgGoodsSpecs/store', 'OrgGoodsSpecsController@store')->name('admin.orgGoodsSpecs.store');
    $router->get('orgGoodsSpecs/edit/{orgGoodsSpec}', 'OrgGoodsSpecsController@edit')->name('admin.orgGoodsSpecs.edit');//隐式绑定
    $router->post('orgGoodsSpecs/update/{orgGoodsSpec}', 'OrgGoodsSpecsController@update')->name('admin.orgGoodsSpecs.update');//隐式绑定
    $router->get('orgGoodsSpecs/destroy/{orgGoodsSpec}', 'OrgGoodsSpecsController@destroy')->name('admin.orgGoodsSpecs.destroy');//隐式绑定
    $router->post('orgGoodsSpecs/destroyBat', 'OrgGoodsSpecsController@destroyBat')->name('admin.orgGoodsSpecs.destroyBat');

    ## 路由：UploadFile 图片库
    $router->get('uploadFiles', 'UploadFilesController@index')->name('admin.uploadFiles');
    $router->get('uploadFiles/create', 'UploadFilesController@create')->name('admin.uploadFiles.create');
    $router->get('uploadFiles/list', 'UploadFilesController@list')->name('admin.uploadFiles.list');
    $router->post('uploadFiles/store', 'UploadFilesController@store')->name('admin.uploadFiles.store');
    $router->get('uploadFiles/edit/{uploadFile}', 'UploadFilesController@edit')->name('admin.uploadFiles.edit');//隐式绑定
    $router->post('uploadFiles/update/{uploadFile}', 'UploadFilesController@update')->name('admin.uploadFiles.update');//隐式绑定
    $router->get('uploadFiles/destroy/{uploadFile}', 'UploadFilesController@destroy')->name('admin.uploadFiles.destroy');//隐式绑定
    $router->post('uploadFiles/destroyBat', 'UploadFilesController@destroyBat')->name('admin.uploadFiles.destroyBat');
    $router->post('uploadFiles/updateBat', 'UploadFilesController@updateBat')->name('admin.uploadFiles.updateBat');
    $router->get('uploadFiles/picGroup', 'UploadFilesController@picGroup')->name('admin.uploadFiles.picGroup');
    $router->get('uploadFiles/picWindow', 'UploadFilesController@picWindow')->name('admin.uploadFiles.picWindow');
    #文件上传相关路由
    $router->post('uploadFiles/uploader', 'UploadController@uploadFilesUploader')->name('admin.uploadFiles.uploader');


    ## 路由：UploadGroup 图片分组
    $router->get('uploadGroups', 'UploadGroupsController@index')->name('admin.uploadGroups');
    $router->get('uploadGroups/create', 'UploadGroupsController@create')->name('admin.uploadGroups.create');
    $router->get('uploadGroups/createWindow', 'UploadGroupsController@createWindow')->name('admin.uploadGroups.createWindow');
    $router->get('uploadGroups/list', 'UploadGroupsController@list')->name('admin.uploadGroups.list');
    $router->post('uploadGroups/store', 'UploadGroupsController@store')->name('admin.uploadGroups.store');
    $router->get('uploadGroups/edit/{uploadGroup}', 'UploadGroupsController@edit')->name('admin.uploadGroups.edit');//隐式绑定
    $router->post('uploadGroups/update/{uploadGroup}', 'UploadGroupsController@update')->name('admin.uploadGroups.update');//隐式绑定
    $router->get('uploadGroups/destroy/{uploadGroup}', 'UploadGroupsController@destroy')->name('admin.uploadGroups.destroy');//隐式绑定
    $router->post('uploadGroups/destroyBat', 'UploadGroupsController@destroyBat')->name('admin.uploadGroups.destroyBat');
    $router->get('uploadGroups/createWindow', 'UploadGroupsController@createWindow')->name('admin.uploadGroups.createWindow');
    $router->get('uploadGroups/editWindow/{uploadGroup}', 'UploadGroupsController@editWindow')->name('admin.uploadGroups.editWindow');//隐式绑定


    ## 路由：BuyerAddress
    $router->get('buyerAddresses', 'BuyerAddressesController@index')->name('admin.buyerAddresses');
    $router->post('buyerAddresses/province', 'BuyerAddressesController@province')->name('admin.buyerAddresses.province');
    $router->get('buyerAddresses/city/{city}', 'BuyerAddressesController@city')->name('admin.buyerAddresses.city');
    $router->get('buyerAddresses/create', 'BuyerAddressesController@create')->name('admin.buyerAddresses.create');
    $router->get('buyerAddresses/list', 'BuyerAddressesController@list')->name('admin.buyerAddresses.list');
    $router->post('buyerAddresses/store', 'BuyerAddressesController@store')->name('admin.buyerAddresses.store');
    $router->get('buyerAddresses/edit/{buyerAddress}', 'BuyerAddressesController@edit')->name('admin.buyerAddresses.edit');//隐式绑定
    $router->post('buyerAddresses/update/{buyerAddress}', 'BuyerAddressesController@update')->name('admin.buyerAddresses.update');//隐式绑定
    $router->get('buyerAddresses/destroy/{buyerAddress}', 'BuyerAddressesController@destroy')->name('admin.buyerAddresses.destroy');//隐式绑定
    $router->post('buyerAddresses/destroyBat', 'BuyerAddressesController@destroyBat')->name('admin.buyerAddresses.destroyBat');
    ## 路由：Specs
    $router->get('specs', 'SpecsController@index')->name('admin.specs');
    $router->get('specs/create', 'SpecsController@create')->name('admin.specs.create');
    $router->get('specs/list', 'SpecsController@list')->name('admin.specs.list');
    $router->post('specs/store', 'SpecsController@store')->name('admin.specs.store');
    $router->get('specs/edit/{specs}', 'SpecsController@edit')->name('admin.specs.edit');//隐式绑定
    $router->post('specs/update/{specs}', 'SpecsController@update')->name('admin.specs.update');//隐式绑定
    $router->get('specs/destroy/{specs}', 'SpecsController@destroy')->name('admin.specs.destroy');//隐式绑定
    $router->post('specs/destroyBat', 'SpecsController@destroyBat')->name('admin.specs.destroyBat');
    ## 路由：SpecValues
    $router->get('specValues', 'SpecValuesController@index')->name('admin.specValues');
    $router->get('specValues/create', 'SpecValuesController@create')->name('admin.specValues.create');
    $router->get('specValues/list', 'SpecValuesController@list')->name('admin.specValues.list');
    $router->post('specValues/store', 'SpecValuesController@store')->name('admin.specValues.store');
    $router->get('specValues/edit/{specValues}', 'SpecValuesController@edit')->name('admin.specValues.edit');//隐式绑定
    $router->post('specValues/update/{specValues}', 'SpecValuesController@update')->name('admin.specValues.update');//隐式绑定
    $router->get('specValues/destroy/{specValues}', 'SpecValuesController@destroy')->name('admin.specValues.destroy');//隐式绑定
    $router->post('specValues/destroyBat', 'SpecValuesController@destroyBat')->name('admin.specValues.destroyBat');
    ## 路由：Favoriters
    $router->get('favorites', 'FavoritesController@index')->name('admin.favorites');
    $router->get('favorites/create', 'FavoritesController@create')->name('admin.favorites.create');
    $router->get('favorites/list', 'FavoritesController@list')->name('admin.favorites.list');
    $router->post('favorites/store', 'FavoritesController@store')->name('admin.favorites.store');
    $router->get('favorites/edit/{favorites}', 'FavoritesController@edit')->name('admin.favorites.edit');//隐式绑定
    $router->post('favorites/update/{favorites}', 'FavoritesController@update')->name('admin.favorites.update');//隐式绑定
    $router->get('favorites/destroy/{favorites}', 'FavoritesController@destroy')->name('admin.favorites.destroy');//隐式绑定
    $router->post('favorites/destroyBat', 'FavoritesController@destroyBat')->name('admin.favorites.destroyBat');



    ## 路由：OperationLog
    $router->get('operationLogs', 'OperationLogsController@index')->name('admin.operationLogs');
    $router->get('operationLogs/create', 'OperationLogsController@create')->name('admin.operationLogs.create');
    $router->get('operationLogs/list', 'OperationLogsController@list')->name('admin.operationLogs.list');
    $router->post('operationLogs/store', 'OperationLogsController@store')->name('admin.operationLogs.store');
    $router->get('operationLogs/edit/{operationLog}', 'OperationLogsController@edit')->name('admin.operationLogs.edit');
    $router->post('operationLogs/update/{operationLog}', 'OperationLogsController@update')->name('admin.operationLogs.update');
    $router->get('operationLogs/destroy/{operationLog}', 'OperationLogsController@destroy')->name('admin.operationLogs.destroy');
    $router->post('operationLogs/destroyBat', 'OperationLogsController@destroyBat')->name('admin.operationLogs.destroyBat');

    ## 路由：Product
    $router->get('products', 'ProductsController@index')->name('admin.products');                         //列表
    $router->get('products/create', 'ProductsController@create')->name('admin.products.create');         //添加列表
    $router->get('products/list', 'ProductsController@list')->name('admin.products.list');
    $router->post('products/store', 'ProductsController@store')->name('admin.products.store');
    $router->get('products/edit/{product}', 'ProductsController@edit')->name('admin.products.edit');//隐式绑定
    $router->post('products/update/{product}', 'ProductsController@update')->name('admin.products.update');//隐式绑定
    $router->get('products/destroy/{product}', 'ProductsController@destroy')->name('admin.products.destroy');//隐式绑定
    $router->post('products/destroyBat', 'ProductsController@destroyBat')->name('admin.products.destroyBat');

    ## 路由：Stock
    $router->get('stocks', 'StocksController@index')->name('admin.stocks');
    $router->get('stocks/create', 'StocksController@create')->name('admin.stocks.create');
    $router->get('stocks/list', 'StocksController@list')->name('admin.stocks.list');
    $router->post('stocks/store', 'StocksController@store')->name('admin.stocks.store');
    $router->get('stocks/edit/{stock}', 'StocksController@edit')->name('admin.stocks.edit');//隐式绑定
    $router->post('stocks/update/{stock}', 'StocksController@update')->name('admin.stocks.update');//隐式绑定
    $router->get('stocks/destroy/{stock}', 'StocksController@destroy')->name('admin.stocks.destroy');//隐式绑定
    $router->post('stocks/destroyBat', 'StocksController@destroyBat')->name('admin.stocks.destroyBat');

    ## 路由：LoginLog
    $router->get('loginLogs', 'LoginLogsController@index')->name('admin.loginLogs');
    $router->get('loginLogs/create', 'LoginLogsController@create')->name('admin.loginLogs.create');
    $router->get('loginLogs/list', 'LoginLogsController@list')->name('admin.loginLogs.list');
    $router->post('loginLogs/store', 'LoginLogsController@store')->name('admin.loginLogs.store');
    $router->get('loginLogs/edit/{loginLog}', 'LoginLogsController@edit')->name('admin.loginLogs.edit');//隐式绑定
    $router->post('loginLogs/update/{loginLog}', 'LoginLogsController@update')->name('admin.loginLogs.update');//隐式绑定
    $router->get('loginLogs/destroy/{loginLog}', 'LoginLogsController@destroy')->name('admin.loginLogs.destroy');//隐式绑定
    $router->post('loginLogs/destroyBat', 'LoginLogsController@destroyBat')->name('admin.loginLogs.destroyBat');

    ## 路由：Order
    $router->get('orders', 'OrdersController@index')->name('admin.orders');
    $router->get('orders/create', 'OrdersController@create')->name('admin.orders.create');
    $router->get('orders/list', 'OrdersController@list')->name('admin.orders.list');
    $router->post('orders/store', 'OrdersController@store')->name('admin.orders.store');
    $router->get('orders/edit/{order}', 'OrdersController@edit')->name('admin.orders.edit');//隐式绑定
    $router->get('orders/detail/{order}', 'OrdersController@detail')->name('admin.orders.detail');//隐式绑定
    $router->post('orders/update/{order}', 'OrdersController@update')->name('admin.orders.update');//隐式绑定
    $router->get('orders/destroy/{order}', 'OrdersController@destroy')->name('admin.orders.destroy');//隐式绑定
    $router->post('orders/destroyBat', 'OrdersController@destroyBat')->name('admin.orders.destroyBat');

    ## 路由：Category
    $router->get('categories', 'CategoriesController@index')->name('admin.categories');
    $router->get('categories/create', 'CategoriesController@create')->name('admin.categories.create');
    $router->get('categories/create/window', 'CategoriesController@createWindow')->name('admin.categories.create.window');
    $router->get('categories/list', 'CategoriesController@list')->name('admin.categories.list');
    $router->get('categories/all', 'CategoriesController@categoryAll')->name('admin.categories.all');
    $router->post('categories/store', 'CategoriesController@store')->name('admin.categories.store');
    $router->get('categories/edit/{category}', 'CategoriesController@edit')->name('admin.categories.edit');
    $router->post('categories/update/{category}', 'CategoriesController@update')->name('admin.categories.update');
    $router->get('categories/destroy/{category}', 'CategoriesController@destroy')->name('admin.categories.destroy');
    $router->post('categories/destroyBat', 'CategoriesController@destroyBat')->name('admin.categories.destroyBat');

    ## 路由：Buyer
    $router->get('buyers', 'BuyersController@index')->name('admin.buyers');
    $router->get('buyers/create', 'BuyersController@create')->name('admin.buyers.create');
    $router->get('buyers/list', 'BuyersController@list')->name('admin.buyers.list');
    $router->post('buyers/store', 'BuyersController@store')->name('admin.buyers.store');
    $router->get('buyers/edit/{buyer}', 'BuyersController@edit')->name('admin.buyers.edit');//隐式绑定
    $router->post('buyers/update/{buyer}', 'BuyersController@update')->name('admin.buyers.update');//隐式绑定
    $router->get('buyers/destroy/{buyer}', 'BuyersController@destroy')->name('admin.buyers.destroy');//隐式绑定
    $router->post('buyers/destroyBat', 'BuyersController@destroyBat')->name('admin.buyers.destroyBat');

    ## 路由：Goods
    $router->get('goods', 'GoodsController@index')->name('admin.goods');
    $router->get('goods/create', 'GoodsController@create')->name('admin.goods.create');
    $router->get('goods/list', 'GoodsController@list')->name('admin.goods.list');
    $router->post('goods/store', 'GoodsController@store')->name('admin.goods.store');
    $router->get('goods/edit/{goods}', 'GoodsController@edit')->name('admin.goods.edit');//隐式绑定
    $router->get('goods/detail/{goods}', 'GoodsController@detail')->name('admin.goods.detail');//隐式绑定
    $router->post('goods/update/{goods}', 'GoodsController@update')->name('admin.goods.update');//隐式绑定
    $router->get('goods/destroy/{goods}', 'GoodsController@destroy')->name('admin.goods.destroy');//隐式绑定
    $router->post('goods/destroyBat', 'GoodsController@destroyBat')->name('admin.goods.destroyBat');

    ## 路由：address
    $router->get('address', 'AddressController@index')->name('admin.address');
    $router->get('address/create', 'AddressController@create')->name('admin.address.create');    //添加列表
    $router->post('address/store', 'AddressController@store')->name('admin.address.store');      //添加
    $router->get('address/list', 'AddressController@list')->name('admin.address.list');
    $router->get('address/edit/{address}', 'AddressController@edit')->name('admin.address.edit');//编辑列表
    $router->post('address/update/{address}', 'AddressController@update')->name('admin.address.update');//编辑
    $router->get('address/destroy/{address}','AddressController@destroy')->name('admin.address.destroy');//单个删除


    ##路由: deliveries
    $router->get('deliveries','DeliveriesController@index')->name('admin.deliveries');        //首页
    $router->get('deliveries/create','DeliveriesController@create')->name('admin.deliveries.create');        //运费模板页面
    $router->post('deliveries/store','DeliveriesController@store')->name('admin.deliveries.store');        //添加
    $router->get('deliveries/list', 'DeliveriesController@list')->name('admin.deliveries.list');
    $router->get('deliveries/edit/{deliveries}', 'DeliveriesController@edit')->name('admin.deliveries.edit');//编辑页面
    $router->post('deliveries/update/{deliveries}', 'DeliveriesController@update')->name('admin.deliveries.update');//编辑
    $router->get('deliveries/destroy/{deliveries}','DeliveriesController@destroy')->name('admin.deliveries.destroy');//单个删除
    $router->post('deliveries/destroyBat', 'DeliveriesController@destroyBat')->name('admin.deliveries.destroyBat'); //批量删除

    ##路由： deliveries_rules
    $router->get('delivery_rules','DeliveryRulesController@index')->name('admin.delivery_rules');        //运费规则列表
    $router->get('delivery_rules/list', 'DeliveryRulesController@list')->name('admin.delivery_rules.list');

    ## 路由：Brand  #品牌管理
    $router->get('brands', 'BrandsController@index')->name('admin.brands');
    $router->get('brands/create', 'BrandsController@create')->name('admin.brands.create');
    $router->get('brands/list', 'BrandsController@list')->name('admin.brands.list');
    $router->post('brands/store', 'BrandsController@store')->name('admin.brands.store');
    $router->get('brands/edit/{brand}', 'BrandsController@edit')->name('admin.brands.edit');//隐式绑定
    $router->post('brands/update/{brand}', 'BrandsController@update')->name('admin.brands.update');//隐式绑定
    $router->get('brands/destroy/{brand}', 'BrandsController@destroy')->name('admin.brands.destroy');//隐式绑定
    $router->post('brands/destroyBat', 'BrandsController@destroyBat')->name('admin.brands.destroyBat');

    ## 路由：sellers 卖家管理
    $router->get('sellers', 'SellersController@index')->name('admin.sellers');
    $router->get('sellers/create', 'SellersController@create')->name('admin.sellers.create');
    $router->get('sellers/list', 'SellersController@list')->name('admin.sellers.list');
    $router->post('sellers/store', 'SellersController@store')->name('admin.sellers.store');
    $router->get('sellers/edit/{user}', 'SellersController@edit')->name('admin.sellers.edit');//隐式绑定
    $router->post('sellers/update/{user}', 'SellersController@update')->name('admin.sellers.update');//隐式绑定
    $router->get('sellers/destroy/{user}', 'SellersController@destroy')->name('admin.sellers.destroy');//隐式绑定
    $router->post('sellers/destroyBat', 'SellersController@destroyBat')->name('admin.sellers.destroyBat');
    $router->get('sellers/password/{user}', 'SellersController@showPasswordFormPage')->name('admin.sellers.password.edit');//隐式绑定
    $router->post('sellers/password/{user}', 'SellersController@passwordRequest')->name('admin.sellers.password.update');//隐式绑定

    ## 路由：Refund
    $router->get('refunds', 'RefundsController@index')->name('admin.refunds');
    $router->get('refunds/create', 'RefundsController@create')->name('admin.refunds.create');
    $router->get('refunds/list', 'RefundsController@list')->name('admin.refunds.list');
    $router->post('refunds/store', 'RefundsController@store')->name('admin.refunds.store');
    $router->get('refunds/edit/{refund}', 'RefundsController@edit')->name('admin.refunds.edit');//隐式绑定
    $router->get('refunds/detail/{refund}', 'RefundsController@detail')->name('admin.refunds.detail');//隐式绑定
    $router->post('refunds/update/{refund}', 'RefundsController@update')->name('admin.refunds.update');//隐式绑定
    $router->post('refunds/operate/{refund}', 'RefundsController@operate')->name('admin.refunds.operate');//隐式绑定
    $router->get('refunds/destroy/{refund}', 'RefundsController@destroy')->name('admin.refunds.destroy');//隐式绑定
    $router->post('refunds/destroyBat', 'RefundsController@destroyBat')->name('admin.refunds.destroyBat');

    ## 路由：WxPayReport
    $router->get('wxPayReports', 'WxPayReportsController@index')->name('admin.wxPayReports');
    $router->get('wxPayReports/create', 'WxPayReportsController@create')->name('admin.wxPayReports.create');
    $router->get('wxPayReports/list', 'WxPayReportsController@list')->name('admin.wxPayReports.list');
    $router->post('wxPayReports/store', 'WxPayReportsController@store')->name('admin.wxPayReports.store');
    $router->get('wxPayReports/edit/{wxPayReport}', 'WxPayReportsController@edit')->name('admin.wxPayReports.edit');//隐式绑定
    $router->post('wxPayReports/update/{wxPayReport}', 'WxPayReportsController@update')->name('admin.wxPayReports.update');//隐式绑定
    $router->get('wxPayReports/destroy/{wxPayReport}', 'WxPayReportsController@destroy')->name('admin.wxPayReports.destroy');//隐式绑定
    $router->post('wxPayReports/destroyBat', 'WxPayReportsController@destroyBat')->name('admin.wxPayReports.destroyBat');

    //支付订单查询
    $router->get('wxPayReports/orderQuery/{wxPayReport}', 'WxPayReportsController@orderQuery')->name('admin.wxPayReports.orderQuery');


    ## 路由：PayNotify 支付通知数据
    $router->get('payNotifies', 'PayNotifiesController@index')->name('admin.payNotifies');
    $router->get('payNotifies/create', 'PayNotifiesController@create')->name('admin.payNotifies.create');
    $router->get('payNotifies/list', 'PayNotifiesController@list')->name('admin.payNotifies.list');
    $router->post('payNotifies/store', 'PayNotifiesController@store')->name('admin.payNotifies.store');
    $router->get('payNotifies/edit/{payNotify}', 'PayNotifiesController@edit')->name('admin.payNotifies.edit');//隐式绑定
    $router->post('payNotifies/update/{payNotify}', 'PayNotifiesController@update')->name('admin.payNotifies.update');//隐式绑定
    $router->get('payNotifies/destroy/{payNotify}', 'PayNotifiesController@destroy')->name('admin.payNotifies.destroy');//隐式绑定
    $router->post('payNotifies/destroyBat', 'PayNotifiesController@destroyBat')->name('admin.payNotifies.destroyBat');
    $router->get('payNotifies/refund/{payNotify}', 'PayNotifiesController@refund')->name('admin.payNotifies.refund');

    # 运费模板
    $router->get('fare', 'fareController@index')->name('admin.fare');    //首页
    $router->get('fare/create', 'fareController@create')->name('admin.fare.create');    //添加页面
    $router->post('fare/store', 'fareController@store')->name('admin.fare.store');    //添加
    $router->get('fare/list', 'fareController@list')->name('admin.fare.list');
    $router->get('fare/edit', 'fareController@edit')->name('admin.fare.edit');  //编辑页面
    $router->post('fare/update', 'fareController@update')->name('admin.fare.update');

    ## 路由：PayRefund
    $router->get('payRefunds', 'PayRefundsController@index')->name('admin.payRefunds');
    $router->get('payRefunds/create', 'PayRefundsController@create')->name('admin.payRefunds.create');
    $router->get('payRefunds/list', 'PayRefundsController@list')->name('admin.payRefunds.list');
    $router->post('payRefunds/store', 'PayRefundsController@store')->name('admin.payRefunds.store');
    $router->get('payRefunds/edit/{payRefund}', 'PayRefundsController@edit')->name('admin.payRefunds.edit');//隐式绑定
    $router->post('payRefunds/update/{payRefund}', 'PayRefundsController@update')->name('admin.payRefunds.update');//隐式绑定
    $router->get('payRefunds/destroy/{payRefund}', 'PayRefundsController@destroy')->name('admin.payRefunds.destroy');//隐式绑定
    $router->post('payRefunds/destroyBat', 'PayRefundsController@destroyBat')->name('admin.payRefunds.destroyBat');


});