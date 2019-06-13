<?php

namespace App\Listeners;

use App\Events\OrderSendEvent;
use App\Lib\Wln\WLnTrade;

class OrderSendEventListener
{
    private $wlnTradePush;

    /**
     * Create the event listener.
     *
     * @param WLnTrade $wlnTradePush
     */
    public function __construct(WLnTrade $wlnTradePush)
    {
        //
        $this->wlnTradePush = $wlnTradePush;
    }

    /**
     * Handle the event.
     *
     * @param  OrderSendEvent $event
     * @return void
     */
    public function handle(OrderSendEvent $event)
    {
        //订单
        $order = $event->getOrder();
        //转换成ERP可接受格式
        $trade = $this->tradeData($order);
        //设置订单集合
        $this->wlnTradePush->setTrades([$trade]);
        //推送订单
        $res = $this->wlnTradePush->push();
        if ($res['success']) {
            \Log::info(__CLASS__ . ': success push:' . json_encode($res));
        } else {
            \Log::info(__CLASS__ . ': fail push:' . json_encode($res));
        }


    }

    /**
     * Order 转化未万里牛订单格式
     * @param $order
     * @return array
     */
    public function tradeData($order)
    {

        $data = [
            'tradeID' => $order->order_no, // 第三方交易号
            'shopNick' => config('bs.erp.shop_nick'), // 对应到 ERP 中的店铺昵称
            'status' => 1, // 交易状态:0：未创建交易；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；6：已签收
            'createTime' => time(), // 交易创建时间，毫秒级时间戳，如 1421585369113
//                'payTime'          => 1551337430123, // 非必填，付款后必填，交易付款时间，毫秒级时间戳，如 1421585369113，付款后才会有值，其他状态不传
//                'endTime'          => 0, // 非必填，结束后必填，交易结束时间，毫秒级时间戳，如 1421585369113，结束后才会有值，其他状态不传
            'modifyTime' => time(), // 交易修改时间，毫秒级时间戳，如 1421585369113，订单每次修改更新该值
//                'shippingTime'     => 0, // 非必填，发货后必填，交易发货时间，毫秒级时间戳，如 1421585369113，发货后才会有值，其他状态不传
//                'storeID'          => '', // 非必填，仓库编码，与系统基础信息仓库相对应
//                'sellerMemo'       => '', // 非必填，卖家备注
            'shippingType' => 0, // 发货类型:0：快递；1：EMS；2：平邮；9：卖家承担运费（包邮）；11：虚拟物品；121：自提；122：商家自送（门店配送）
            'totalFee' => $order->total_fee, // 商品总金额，不含邮费
            'postFee' => $order->post_fee, // 邮费
            'payment' => $order->paid_fee, // 非必填，买家最后实际支付金额
            'discountFee' => $order->discount_fee, // 非必填，总的优惠金额
            'buyer' => $order->buyer, // 买家
            'buyerMessage' => $order->buyer_msg, // 非必填，买家备注
//                'buyerEmail'       => '', // 非必填，买家邮箱
            'receiverName' => $order->address->receiver, // 收件人
            'receiverProvince' => $order->address->province, // 收件地址：省
            'receiverCity' => $order->address->city, // 收件地址：市
            'receiverArea' => $order->address->district, // 收件地址：区
            'receiverAddress' => $order->address->detail, // 收件详细地址
//                'receiverZip'      => '', // 非必填，收件地址：邮编
            'receiverMobile' => $order->address->mobile, // 收件人手机
//                'receiverPhone'    => '', // 非必填，收件人座机
//                'identityNum'      => '', // 非必填，身份证号码
            'hasRefund' => 0, // 退款退货标记，1：退款；0：未退款
//                'invoice'          => '', // 非必填，发票抬头

        ];

        foreach ($order->items as $item) {
            $orderItemTpl = [
                'tradeID' => $item->order_no, // 交易号
                'orderID' => $item->item_no, // 子交易号
                'itemID' => '4245', // 商品编号
                'itemTitle' => $item->goods_name, // 商品标题，如万里牛
//                        'itemCode'  => 'TEST0002', // 非必填，商品标题，如万里牛
//                        'skuID'     => '', // 非必填，多规格必填，规格编号
//                        'skuTitle'  => 'M', // 非必填，规格值，如红色,M
                'skuCode' => $item->spec_code, // 非必填，规格商家编码
                'status' => 1, // 明细状态: 0：未创建订单；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；
//                        'hasRefund' => 0, // 非必填，是否为退款/退货明细，0：无退款；1：有退款
                'price' => $item->goods_price, // 商品单价
                'size' => $item->num, // 数量
//                        'snapshot'  => '', // 非必填，商品链接或者快照链接
                'imageUrl' => $item->image_url, // 商品图片地址
                'payment' => $item->payment, // 明细实付
            ];
            $data['orders'][] = $orderItemTpl;
        }
        return $data;

    }
}
