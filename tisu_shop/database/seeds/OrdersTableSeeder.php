<?php

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=OrdersTableSeeder
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'production') {
            dd('生产环境, 不能执行');
            return;
        }
        $buyers = \App\Models\Buyer::orderBy('id', 'asc')->limit(5)->get();
        if ($buyers) {
            foreach ($buyers as $buyer) {
                $model = $this->create($buyer);
                echo $model->order_no . PHP_EOL;
            }
        }
    }

    public function create(\App\Models\Buyer $buyer)
    {

        $row = [
            "order_no" => Order::findAvailableNo(),
            "shop_name" => "店铺名称001",
            "shop_nick" => "店铺昵称001",
            "source" => Order::SOURCE_WE_CHAT_APPLET,
            "total_fee" => 1000,
            "paid_fee" => 810,
            "discount_fee" => 200,
            "post_fee" => 10,
            "service_fee" => 0,
            "pay_status" => Order::PAY_STATUS_WAIT,
//            "pay_time" => 0,
            "express_price" => rand(3, 10),
            "express_company" => ['STO', 'SF'][rand(0, 1)],
//            "express_no" => "",
            "send_status" => Order::SEND_STATUS_WAIT,
//            "send_time" => time(),
            "receipt_status" => Order::RECEIPT_STATUS_WAIT,
//            "receipt_time" =>time(),
            "refund_status" => Order::REFUND_STATUS_WAIT,
            "order_status" => Order::ORDER_STATUS_WAIT,
            "order_type" => Order::ORDER_TYPE_REGULAR,
            "close_type" => Order::CLOSE_TYPE_NO,
            "close_time" => 0,
            "create_time" => time(),
            "update_time" => time(),
            "buyer_msg" => "测试下单",
            "seller_msg" => "测试销售",
            "buyer" => $buyer->nick_name,
            "user_id" => $buyer->id,
            "shop_id" => $buyer->shop_id,
        ];

        $model = Order::create($row);

        //订单地址
        $addressData = [
            'order_no' => $model->order_no,
            'receiver' => 'JJG',
            'mobile' => '15869021868',
            'shop_id' => 1,
            'province' => '浙江',
            'city' => "杭州市",
            'district' => "江干区",
            'detail' => "东谷创业园",
            'buyer_id' => $buyer->id,
        ];


        //退单地址
        $refundAddressData = [
            'refund_no' => '111111',
            'receiver' => 'JJG',
            'mobile' => '15869021868',
            'shop_id' => 1,
            'province' => '浙江',
            'city' => "杭州市",
            'district' => "江干区",
            'detail' => "东谷创业园",
            'buyer_id' => $buyer->id,
        ];




        //'order_no', 'receiver', 'mobile', 'phone', 'province', 'city', 'district', 'detail', 'buyer_id', 'create_time', 'shop_id'
        $orderAddress = \App\Models\OrderAddress::create($addressData);

        $refundAddress=\App\Models\RefundAddresses::create($refundAddressData);

        // 订单明细创建
        $items = [
            [
                "order_id"=>$model->id,
                "order_no" => $model->order_no,
                "item_no" => OrderItem::createItemNo($model->order_no, 1),
                "goods_id" => rand(1, 10),
                "goods_name" => "商品名称",
                "image_url" => "商品图片地址",
//                "deduct_stock_type" => OrderItem::DEDUCT_STOCK_TYPE_CREATE,
//                "spec_type" => OrderItem::SPEC_TYPE_SINGLE,
                "spec_code" => "SPH00005384",
                "goods_spec_id" => 1,
                "goods_no" => "SPH00005384",
                "goods_price" => 100,
                "line_price" => 160,
//                "weight" => 0.01,
                "num" => rand(1, 10),
                "receivable" => 200,
                "payment" => 150,
                "user_id" => $buyer->id,
                "shop_id" => $buyer->shop_id,
                "create_time" => time(),
                "status" => OrderItem::STATUS_WAIT,
                "has_refund" => OrderItem::HAS_REFUND_UN_REFUND
            ],
            [
                "order_id"=>$model->id,
                "order_no" => $model->order_no,
                "item_no" => OrderItem::createItemNo($model->order_no, 1),
                "goods_id" => rand(1, 10),
                "goods_name" => "商品名称",
                "image_url" => "商品图片地址",
//                "deduct_stock_type" => OrderItem::DEDUCT_STOCK_TYPE_CREATE,
//                "spec_type" => OrderItem::SPEC_TYPE_SINGLE,
                "spec_code" => "SPH00005384",
                "goods_spec_id" => 1,
                "goods_no" => "SPH00005384",
                "goods_price" => 100,
                "line_price" => 160,
//                "weight" => 0.01,
                "num" => rand(1, 10),
                "receivable" => 200,
                "payment" => 150,
                "user_id" => $buyer->id,
                "shop_id" => $buyer->shop_id,
                "create_time" => time(),
                "status" => OrderItem::STATUS_WAIT,
                "has_refund" => OrderItem::HAS_REFUND_UN_REFUND
            ]
        ];


        $refund=[

                "order_no" => $model->order_no,
                "item_no" => OrderItem::createItemNo($model->order_no, 1),
                "user_id" => $buyer->id,
                "buyer_id"=>$buyer->id,
                "goods_id" => rand(1, 10),
                "goods_spec_id" => 1,
                "image_urls" => "商品图片地址",
                "refund_no"=>OrderItem::createItemNo($model->order_no, 1),
                "refund_way"=>\App\Models\Refund::REFUND_WAY_MONEY,
                "refund_reason"=>\App\Models\Refund::REFUND_REASON_TWO,
                "back_money"=>1000,
                "phone"=>1111111,
                "remark"=>'',
                "status"=>\App\Models\Refund::REFUND_REFUNDING,
                "refund_progress"=>\App\Models\Refund::REFUND_PROGRESS_APPLYING,
                "arrive_time"=>time(),
                "shop_id" => $buyer->shop_id,

        ];


        foreach ($items as $k => $item) {
            $item['item_no'] = OrderItem::createItemNo($model->order_no, $k + 1);
            $orderItem = OrderItem::create($item);

        }

        $refundItem=\App\Models\Refund::create($refund);

        return $model;
    }
}
