## 缇苏商城后端（tisu shop）

- 立项时间：2019-03-01
- [项目沟通wiki](https://www.yuque.com/dashboard)
- [项目地址](http://git.eziyan.top/ml/tisu_shop)

#前期准备：

## 对接【万里牛ERP】

- 获取商品数据
- 检验商品库存
- 推送商城订单
- 获取订单状态-物流单号

## 对接APP（红人使用） 

- 登录登出接口
- 提供店铺信息
- 提供选款商品
- 提供订单信息
- 提供统计信息

### 对接微信商城小程序后端 [leancloud](https://leancloud.cn/)

## 后台操作
- 创建APP用户-红人
- 创建小程序记录 app_key等信息
- 关联用户和小程序
- 创建店铺 - 关联小程序/用户/万里牛ERP的店铺或者仓库
- 商品管理 - 商品原数据（WLN ）/ 红人选款商品（在仓库，上架）
- 订单管理 - 查询，统计

## APP用户认证方式
- [JWT](https://blog.csdn.net/maguanghui_2012/article/details/80740788)


## 注意

- 关于商品是否允许超卖（库存使用哪个仓库的库存）
- 设置虚拟库存是否能超过可用库存（多个店铺同一个SKU的设置的虚拟库存总和）


## 科普
[关于Oauth 2和JWT](https://blog.csdn.net/ljinddlj/article/details/53108261) 


### 项目部署

#### 执行的脚本
```
php artisan migrate
php artisan db:seed --class=PermissionsTableSeeder
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=RegionsTableSeeder
```

#### 测试环境 定时任务脚本
```
php artisan schedule:run

```

#### 创建订单(生产环境无效)
```
php artisan db:seed --class=OrdersTableSeeder
```

#### 推送订单给ERP(万里牛)
```
php artisan order:push T2019031617273286542

```

### 系统配置
```
QUEUE_DRIVER=database
```
### redis 选10号库(0-15)
```
'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 10,
        ],

    ],
```

#### 微信支付扩展包(不合适)
```
composer require "overtrue/laravel-wechat:~4.0"
```


#### 启动 swoole-http-server命令：

```
php artisan swoole:http start

php artisan swoole:http restart

php artisan swoole:http reload

ps aux|grep "swoole"

```


#### 关于API的调试-自动登录
>使用方法: 要求登录的接口里参数增加 - {debugger:user_id}

代码实现-服务提供者 app/Providers/AutoLoginServiceProvider.php

在 app/Providers/AppServiceProvider.php 中注册
```
/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 非生产环境 注册 自动登录服务
        if ($this->app->environment() !== 'production')
            $this->app->register(AutoLoginServiceProvider::class);
    }
    
```