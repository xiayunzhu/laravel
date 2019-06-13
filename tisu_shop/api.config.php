<?php
return [
    'name' => 'sdk',
    'db' => include(__DIR__ . '/data/config/database.php'),
    'src' => 'app/controller',
    'copyright' => "/*
不要手动修改
*/\r\n",
    'platform' => [
        'android' => [
            'dist' => '/tools/sdk/android',
            'package' => 'com.zhiyue.api',
        ],
        'ios' => [
            'dist' => '/tools/sdk/ios',
        ],
    ],
    'tables' => [
//        'orders' => ['order_items?' => '/order_items', 'address?' => '/order_addresses'],
//        'refunds' => ['order_items?' => '/order_items', 'buyer?' => '/buyers', 'order?' => '/orders'],
//        'shop' => ['shops?' => '/shops'],
//        'userInfo' => ['users?' => '/users'],
//        'manage' => ['shop_manages?' => '/shop_manages'],
//        'buyer' => ['buyers?' => '/buyers'],
//        'promo' => ['promos?' => '/promos'],
//        'turnover' => ['turnovers?' => 'turnovers'],
//        'customer' => ['customer_datas' => 'customer_datas'],

//        'users' => ['shops?' => '/shops']
    ],
    'modules' => [
        'pageParams' => ['page', 'per_page'],//引用方式 @pageParams
        'loginModel' => ['access_token', 'token_type', 'expires_in'],
        'orgGoodsModel' => ['id', 'name', 'org_goods_price_max', 'org_goods_price_min', 'commission_max', 'commission_min', 'logo_image' => '@orgLogoImage', 'labels?' => '@label'],
        'label' => ['id', 'org_goods_id', 'label_value'],
        'orgLogoImage' => ['id', 'org_goods_id', 'file_url'],
        'orgMainImages' => ['id', 'goods_id', 'file_url', 'sort'],
        'orgDetailImages' => ['id', 'goods_id', 'file_url', 'sort'],
        'orgHasSpecs' => ['id', 'org_goods_id', 'spec_id', 'spec_value_id', 'created_at', 'updated_at', 'spec' => '@orgSpec', 'spec_value' => '@orgSpecValue'],
        'hasSpecs' => ['id', 'goods_id', 'spec_id', 'spec_value_id', 'spec' => '@orgSpec', 'spec_value' => '@orgSpecValue'],
        'orgSpec' => ['id', 'spec_name'],
        'orgSpecValue' => ['id', 'spec_value'],
        'logoImage' => ['id', 'goods_id', 'file_url', 'sort'],
        'goodLogoImage' => ['id', 'goods_id', 'file_url'],
        'mainImages' => ['id', 'goods_id', 'file_url', 'sort'],
        'detailImages' => ['id', 'goods_id', 'file_url', 'sort', 'is_show'],
        'goodsModel' => ['id', 'publish_status', 'name', 'goods_price', 'sales_actual', 'quantity', 'logo_image' => "@goodLogoImage"],
        'color' => ['color', 'size'],
        'orgGoodDetail' => ['id', 'name', 'commission_rate', 'org_goods_price_max', 'org_goods_price_min', 'commission_max', 'commission_min', 'quantity', 'sold_num', 'main_images?' => '@orgMainImages', 'params?' => '@goodParams', 'specs?' => '@specs', 'specs_params?' => '@specsParams'],
        'goodParams' => ['org_goods_id', 'parameter_name', 'parameter_value'],
        'specsParams' => ['specs_name', 'specs_value'],
        'specs' => ["id", "org_goods_id", "org_goods_price", "spec_name", "quantity", "image_url", "good_specs?" => '@specsInfo'],
        'goodDetailSpecs' => ["id", "publish_status", "sold_num", "quantity", "org_goods_specs_id", "good_specs?" => '@specsInfo'],
        'pic' => ['image_id', 'file_url', 'sort'],
        'specsInfo' => ["org_goods_specs_id", "spec_name", "spec_value"],
        'goodSpecs' => ['id', 'goods_id', 'publish_status', 'size', 'color', 'sold_num', 'quantity', 'org_goods_spec'],
        'goodDetail' => ['id', 'name', 'goods_price', 'category_id', 'brand_id', 'created_at', 'introduction', 'goods_group_id', 'category_name', 'brand_name', 'specs?' => '@goodDetailSpecs', 'logo_image' => '@logoImage', 'main_images?' => '@mainImages', 'detail_images?' => '@detailImages'],
        'goodPreview' => ['id', 'name', 'goods_price', 'introduction', 'has_specs?' => '@hasSpecs', 'main_images?' => '@mainImages', 'detail_images?' => '@detailImages', 'color?' => '@color', 'size'],
        'promoModel' => ["id", "shop_id", "type", "title", "discount", "require_threshold", "credit_limit", "range", "total_count", "take_count", "used_count", "apply_user", "tickets_available", "take_begin", "take_end", "validity_type", "effect_time", "invalid_time", "days", "format", "coupon_id", "status", "explain", "created_at", "updated_at"],
        'promo' => ['id', 'type', 'title', 'discount', 'require_threshold', 'credit_limit', 'range', 'take_count', 'used_count', 'effect_time', 'invalid_time'],
        'orderModel' => ['id', 'order_no', 'total_fee', 'order_status', 'create_time', 'buyer', 'shop_id', 'pay_time', 'send_time', 'receipt_time', 'buyer_msg', 'express_price', 'discount_fee', 'paid_fee', 'user_id', 'order_items?' => '@orderItem', 'address' => '@orderAddress'],
        'orderItem' => ['item_no', 'order_no', 'goods_name', 'goods_price', 'spec_name', 'num', 'image_url'],
        'orderAddress' => ['id', 'order_no', 'receiver', 'mobile', 'province', 'city', 'district', 'detail', 'zip_code'],
        'refundModel' => ['id', 'order_no', 'refund_progress', 'goods_spec_id', 'item_no', 'refund_no', 'refund_reason', 'back_money', 'refund_reason', 'create_at', 'order_item' => '@orderItem', 'buyer' => 'buyer', 'order' => '@order'],
        'order' => ['order_no', 'pay_time', 'total_fee', 'buyer_msg', 'send_time', 'receipt_time', 'create_time', 'express_price', 'address' => '@orderAddress'],
        'buyer' => ['order_no', 'buyer'],
        'orderDatasModel' => ['order_count', 'turnover_total', 'order_total', 'buyer_order', 'buyer_pay', 'order_pay', 'order_send', 'page_view', 'percent', 'ave_price', 'begin_time', 'end_time', 'turnover', 'whole_store', 'pv_order', 'buyer_order_pay'],
        'manageListModel' => ['id', 'shop_id', 'user_id', 'name', 'type', 'status', 'created_at', 'updated_at', 'deleted_at', 'user' => '/users'],
        'smsModel' => ['Message', 'RequestId', 'BizId', 'Code'],
        'manageTypeModel' => ['name', 'type'],
        'buyerInfoModel' => ['id', 'open_id', 'phone', 'union_id', 'nick_name', 'avatar_url', 'gender', 'remark', 'source', 'language', 'country', 'province', 'city', 'address_id', 'shop_id', 'appid', 'sum', 'count', 'avg'],
        'buyerPromoModel' => ['id', 'shop_id', 'buyer_id', 'promo_id', 'effect_time', 'invalid_time', 'status', 'promo' => '/promos'],
        'teasingModel' => ['title', 'content', 'img'],
        'yestDay' => ['new_customer', 'daily_growth', 'all_customer'],
        'messageModel' => ['eid', 'fee', 'num', 'content', 'created_at', '@pageParams'],
        'goodsGroupModel' => ['id', 'name', 'goods_num'],
        'imagesModel' => ['main?' => '@pic', 'detail?' => '@pic'],
        'categoryOption' => ['id', 'name'],
        'pageHome' => ['title', 'type', 'description', 'introduction', 'sort', 'bg_url', 'show_style', 'list?' => '@goodsModule'],
        'goodsModule' => ['title', 'image_url', 'org_goods_id', 'price', 'commission', 'is_new', 'link'],
        'specDetail' => ['org_goods_specs_id', 'spec_name', 'spec_value'],
        'priceChange' => ['id', 'price_change'],
    ],
    'api' => [
        //API 接口
        # 用户登录
        ## 登录验证短信发送
        'auth/verification' => [
            'request' => ['phone'],
            'response' => ['Message', 'RequestId', 'BizId', 'Code'],
        ],

        ## 用户登录
        'auth/login' => [
            'request' => ['phone', 'v_code'],
            'response' => ['access_token', 'token_type', 'expires_in'],
        ],

        ### 个人信息
        'auth/me' => [
            'request' => [],
            'response' => '/users',
        ],

        ### 退出
        'auth/logout' => [
            'request' => [],
            'response' => ['message'],
        ],

        ## 刷新token
        'auth/refresh' => [
            'request' => [],
            'response' => ['access_token', 'token_type', 'expires_in'],
        ],

        ## 商品管理
        ### A1101-选款商品接口
        'orgGoods/list' => [
            'request' => ['category_id', 'sorting', '@pageParams'],
            'response' => ['current_page', 'data?' => '@orgGoodsModel', 'per_page', 'total'],
        ],

        ### A1001仓库商品接口
        'goods/list' => [
            'request' => ['shop_id', 'sales_status', 'publish_status', 'name', '@pageParams'],
            'response' => ['current_page', 'data?' => '@goodsModel', 'per_page', 'total'],
        ],

        ###A1101-添加到仓库或直接上架到店铺接口
        'goods/goodStore' => [
            'request' => ['shop_id', 'org_goods_id', 'publish_status'],
            'response' => ['status', 'message'],
        ],
        ### A1003-SKU添加到仓库
        'goods/goodSpecStore' => [
            'request' => ['shop_id', 'org_goods_id', 'org_goods_spec_ids?' => 'STRING', 'publish_status'],
            'response' => ['status', 'message'],
        ],
//        ### A1003-原商品SKU详情选择信息
//        'orgGoods/spec' => [
//            'request' => ['org_goods_id'],
//            'response' => ['id', 'org_goods_price', 'color', 'size', 'virtual_quantity', 'image_url'],
//        ],
        ### A1003-商品详情页面
        'orgGoods/detail' => [
            'request' => ['id'],
            'response' => '@orgGoodDetail',
        ],
        ### A1009-SKU商品上架下架到店铺接口
        'goods_spec/upper_lower' => [
            'request' => ['goods_specs_id', 'handle'],
            'response' => [],
        ],
        ### A1001-A1007上架下架到店铺接口
        'goods/upper_lower' => [
            'request' => ['goods_id', 'handle'],
            'response' => [],
        ],
        ### A1011-商品编辑(卖家)接口
        'goods_spec/update' => [
            'request' => ['id', 'goods_price', 'line_price', 'virtual_quantity'],
            'response' => [],
        ],
        ### 商品删除(卖家)接口
        'goods/goods' => [
            'request' => ['goods_id'],
            'response' => [],
        ],
        ### A1101-商品分类列表
        'goods/category_list' => [
            'request' => [],
            'response' => ['options?' => '@categoryOption'],
        ],
        ### 商品品牌接口
        'goods/brand_list' => [
            'request' => [],
            'response' => ['options?' => '@categoryOption'],
        ],
        ### A1007-商品编辑接口
        'goods/update' => [
            'request' => ['goods_id', 'brand_id', 'category_id', 'goods_group_id', 'name', 'introduction', 'images' => '@imagesModel'],
            'response' => [],
        ],

        ### A1007-仓库商品详情接口
        'goods/detail' => [
            'request' => ['goods_id'],
            'response' => '@goodDetail',
        ],
//        ### A1009-商品预览页面
//        'goods/preview' => [
//            'request' => ['id'],
//            'response' => '@goodPreview',
//        ],
//        ### A1009-商品SKU详情选择信息
//        'goods/spec' => [
//            'request' => ['goods_id', 'color', 'size'],
//            'response' => ['id', 'goods_price', 'color', 'size', 'image_url'],
//        ],
        ### 商品图片删除：包含A1007仓库商品图片（主图片）、A1010详情页图片删除（详情图片）
        'goods_image/delete' => [
            'request' => ['id'],
            'response' => [],
        ],
        ### 商品图片激活隐藏：A1010详情页图片（详情图片）
        'goods_image/is_show' => [
            'request' => ['id', 'is_show'],
            'response' => [],
        ],
        ### 商品图片排序上移下移动：包含A1007仓库商品图片（主图片）、A1010详情页图片（详情图片）
        'goods_image/sort' => [
            'request' => ['pics?' => ['id', 'sort']],
            'response' => [],
        ],
        ### A1011-SKU商品详情页面
        'goods_spec/detail' => [
            'request' => ['id'],
            'response' => ["id", "org_goods_specs_id", "fx_price", "retail_price", "sold_num", "quantity", "line_price", "goods_price", "virtual_quantity", "good_specs?" => "@specDetail", "org_goods_spec" => "@priceChange"],
        ],

        ## 店铺管理  zhuxy
        ##店铺详情
        'shop/info' => [
            'request' => ['shop_id'],
            'response' => ["id", "shop_code", "shop_nick", "shop_name", "icon_url", "introduction", "user_id", "template", "qr_url", "created_at", "updated_at", "status"],
        ],
        ##店铺信息修改
        'shop/update' => [
            'request' => ['shop_id', 'shop_nick', 'shop_name', 'icon_url', 'introduction'],
            'response' => ["id", "shop_code", "shop_nick", "shop_name", "icon_url", "introduction", "user_id", "template", "qr_url", "created_at", "updated_at", "status"],
        ],
        ##店铺管理员列表
        'manage/list' => [
            'request' => ['shop_id', '@pageParams'],
            'response' => ['current_page', 'data?' => '@manageListModel', 'per_page', 'total'],
        ],
        ##添加管理员
        'manage/store' => [
            'request' => ['shop_id', 'phone', 'v_code'],
            'response' => '/shop_managers',
        ],
        ##修改管理员信息
        'manage/update' => [
            'request' => ['shop_id', 'manage_id', 'name', 'type'],
            'response' => '/shop_managers',
        ],
        ##发送短信
        'manage/sms' => [
            'request' => ['phone'],
            'response' => '@smsModel',
        ],
        ##管理员类型
//        'manage/type_list' => [
//            'request' => [],
//            'response' => ['options?' => '@manageTypeModel'],
//        ],
        ## 客户列表 zhuxy
        'buyer/list' => [
            'request' => ['shop_id', 'nick_name', '@pageParams'],
            'response' => ['current_page', 'data?' => '/buyers', 'per_page', 'total'],
        ],
        ##客户详情
        'buyer/info' => [
            'request' => ['buyer_id'],
            'response' => '@buyerInfoModel',
        ],
        ##修改客户备注
        'buyer/update' => [
            'request' => ['id', 'remark'],
            'response' => '/buyers',
        ],
        ##客户订单
        'orderList' => [
            'request' => ['shop_id', 'buyer_id', '@pageParams'],
            'response' => ['current_page', 'data?' => '@orderModel', 'per_page', 'total'],
        ],
        ##客户优惠券
        'buyerCoupon/list' => [
            'request' => ['shop_id', 'buyer_id', '@pageParams'],
            'response' => ['current_page', 'data?' => '@buyerPromoModel', 'per_page', 'total'],
        ],
        ## 我的 zhuxy
        ##修改短信
        'user/sms' => [
            'request' => ['phone'],
            'response' => '@smsModel',
        ],
        ##修改手机号
        'phoneChange' => [
            'request' => ['phone', 'v_code'],
            'response' => '/users',
        ],
        ##管理店铺列表
        'user/shopList' => [
            'request' => ['@pageParams'],
            'response' => ['current_page', 'data?' => '/shops', 'per_page', 'total'],
        ],
        ##吐槽反馈
        'teasing/store' => [
            'request' => ['title', 'content', 'img'],
            'response' => [],
        ],
        ## 订单管理 yangsc
        ### A0601-订单查询列表
        'orders/appList' => [
            'request' => ['shop_id', 'order_status', 'order_no', '@pageParams'],
            'response' => ['current_page', 'data?' => '@orderModel', 'per_page', 'total'],
        ],
        ### A0601-订单详情
        'orders/detail' => [
            'request' => ['id'],
            'response' => '@orderModel'
        ],
        ### 修改收货地址
        'orders/update_address' => [
            'request' => ['id', 'seller_msg', 'receiver', 'province', 'mobile', 'phone', 'city', 'district', 'detail', 'zip_code'],
            'response' => ['order_no', '@orderAddress'],
        ],
        ### 取消订单
        'orders/cancel_order' => [
            'request' => ['id'],
            'response' => '@orderModel',
        ],

        ## 分组商品 zhaon
        ### A1006-添加分组接口
        'goods_groups_item/store' => [
            'request' => ['shop_id', 'goods_group_id', 'goods_ids?' => 'STRING'],
            'response' => [],
        ],
        ### 删除分组商品接口
        'goods_groups_item/delete' => [
            'request' => ['id'],
            'response' => [],
        ],
        ### 批量删除分组商品接口
        'goods_groups_item/destroyBat' => [
            'request' => ['ids?' => 'STRING'],
            'response' => [],
        ],

        ## 售后订单管理  yangsc
        ### A0602-售后订单查询列表
        'refund/appList' => [
            'request' => ['shop_id', 'refund_status', 'order_no', '@pageParams'],
            'response' => ['current_page', 'data?' => '@refundModel', 'per_page', 'total'],
        ],
        ### A0605-售后订单详情
        'refund/detail_info' => [
            'request' => ['id'],
            'response' => '@refundModel'
        ],
        ### A0604-退款退货处理详情
        'refund/detail' => [
            'request' => ['id'],
            'response' => '@refundModel'
        ],
        ### A0604-退款退货处理（同意，拒绝,关闭申请）
        'refund/refund_process' => [
            'request' => ['id', 'handle', 'refuse_reason'],
            'response' => '@refundModel'
        ],
        ## 商品分组  zhaon
        ### A1004-分组列表
        'goods_groups/list' => [
            'request' => ['shop_id'],
            'response' => ['current_page', 'data?' => '@goodsGroupModel', 'per_page', 'total'],
        ],
        ### 添加分组接口
        'goods_groups/store' => [
            'request' => ['name', 'shop_id'],
            'response' => [],
        ],
        ### 详情分组接口
        'goods_groups/detail' => [
            'request' => ['id'],
            'response' => ["id", "name", "shop_id", "deleted_at", "created_at", "updated_at", "items_count"],
        ],
        ### 更新分组接口
        'goods_groups/update' => [
            'request' => ['id', 'name'],
            'response' => [],
        ],
        ### 删除分组接口
        'goods_groups/delete' => [
            'request' => ['id'],
            'response' => [],
        ],
        ## 消息
        ## 消息通知中心
        'message/index' => [
            'request' => ['shop_id'],
            'response' => ['REFUND' => ['content', 'count', 'created_at'], 'ORDER' => ['content', 'count', 'created_at']],
        ],
        ## 某一类型消息
        'message/list' => [
            'request' => ['shop_id', 'type', '@pageParams'],
            'response' => ['data?' => '@messageModel'],
        ],
        ## 更新消息已读
        'message/update' => [
            'request' => ['shop_id', 'type'],
            'response' => [],
        ],
        ## 营销管理  zhaon
//        ### A0908-优惠券列表
//        'promo/list' => [
//            'request' => ['shop_id', 'type', 'proceed_status'],
//            'response' => ['current_page', 'data?' => '@promo', 'per_page', 'total'],
//        ],
//        ### A0909-添加优惠券
//        'promo/store' => [
//            'request' => ["shop_id", "type", "title", "discount", "require_threshold", "credit_limit", "good_ids", "range", "total_count", "apply_user", "tickets_available", "take_begin", "take_end", "validity_type", "effect_time", "invalid_time", "days", "explain"],
//            'response' => '@promoModel',
//        ],
//        ### A0908-优惠券列表：立即生效操作
//        'promo/effect' => [
//            'request' => ['id'],
//            'response' => '@promoModel',
//        ],
//        ### A0908-优惠券列表：立即删除操作
//        'promo/delete' => [
//            'request' => ['id'],
//            'response' => '@promoModel',
//        ],
        ## 交易数据 yangsc
        ### A0803-交易数据
        'tradeData/trade' => [
            'request' => ['shop_id', 'begin_time', 'end_time'],
            'response' => '@orderDatasModel'
        ],
        ### A0801-营业额
        'tradeData/turnover' => [
            'request' => ['shop_id', 'begin_time', 'end_time'],
            'response' => '@orderDatasModel',
        ],
        ###A0806交易数据统计
        'tradeData/tradeStatistics' => [
            'request' => ['shop_id', 'time', 'page_view'],
            'response' => '@orderDatasModel'
        ],
        ###记录每天的浏览量
        'tradeData/recordPageView' => [
            'request' => ['shop_id', 'time', 'page_view'],
            'response' => '@orderDatasModel'
        ],
        ## 资产管理 zhuxy
        ##总资产
        'turnover' => [
            'request' => ['shop_id'],
            'response' => 'total'
        ],
        ##本月资产明细
        'turnover/info' => [
            'request' => ['shop_id', '@pageParams'],
            'response' => ['current_page', 'data?' => '/turnovers', 'per_page', 'total'],
        ],
        ##筛选资产明细
        'turnover/list' => [
            'request' => ['shop_id', 'begin_time', 'end_time', '@pageParams'],
            'response' => ['current_page', 'data?' => '/turnovers', 'per_page', 'total'],
        ],
        ## 客户数据 zhuxy
        'customer/customer/data' => [
            'request' => ['shop_id'],
            'response' => ['yestDay' => '@yestDay', 'seven' => '@yestDay', 'thirty' => '@yestDay'],
        ],
        ##当天客户量添加
        'customer/buyer/count' => [
            'request' => ['shop_id', 'buyer_increase', 'data'],
            'response' => '/customer_datas',
        ],
        ##客户类别人数
        'customer/count' => [
            'request' => ['shop_id'],
            'response' => ['man_count', 'women_count', 'count', 'hasBuy_count', 'un_bought'],
        ],
        ##首页-选货
        'setting/page/home' => [
            'request' => [],
            'response' => ['modules?' => '@pageHome'],
        ]
        // END API 接口
    ],
];