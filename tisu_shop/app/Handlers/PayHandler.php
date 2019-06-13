<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/2
 * Time: 17:30
 */

namespace App\Handlers;


use App\Events\UnifiedOrderEvent;
use App\Lib\Wx\MinPay\Exception\MiniPayException;
use App\Lib\Wx\MinPay\UnifiedOrderClient;
use App\Lib\Wx\MinPay\WxPayNotifyResult;
use App\Lib\Wx\MinPay\WxPayOrderQuery;
use App\Lib\Wx\MinPay\WxPayRefund;
use App\Models\Order;
use App\Models\PayNotify;
use App\Models\Wxapp;
use App\Models\WxPayReport;

class PayHandler
{
    private $orderHandler;

    public function __construct(OrderHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * @param Order $order
     * @param string $open_id
     * @return array
     * @throws MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function unify(Order $order, string $open_id)
    {
        //Wxapp
        $wxapp = Wxapp::where('shop_id', '=', $order->shop_id)->first();

        $app_id = $wxapp->app_id;
        $mch_id = $wxapp->mchid;
        $api_key = $wxapp->apikey;
        $total_fee = $order->paid_fee * 100;

        $client = new UnifiedOrderClient($app_id, $mch_id, $api_key);

        //是否已发起支持 20190416160351468539
        $wxPayReport = WxPayReport::where([
            ['out_trade_no', '=', $order->order_no],
            ['total_fee', '=', $total_fee],
        ])->first();

        if ($wxPayReport) {
            $data = $client->paymentData(['nonce_str' => $wxPayReport->nonce_str, 'prepay_id' => $wxPayReport->prepay_id]);
        } else {
            $data = $client->unify([
                'body' => '支付执悦商城订单:' . $order->order_no,
                'out_trade_no' => $order->order_no,
                'openid' => $open_id, // 这里的openid为付款人的openid
                'total_fee' => $total_fee, // 总价
            ]);

            //触发 统一下单事件--记录支付报告
            $resultUnify = $client->getUnifyData();
            $resultUnify['out_trade_no'] = $order->order_no;
            $resultUnify['total_fee'] = $total_fee;
            $resultUnify['create_time'] = time();
            $resultUnify['interface_url'] = $client->getEndpoint();

            event(new UnifiedOrderEvent($resultUnify));
        }


        return $data;
    }

    /**
     * @param Wxapp $wxapp
     * @param string $transaction_id
     * @param string $out_trade_no
     * @return mixed
     * @throws MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function query(Wxapp $wxapp, $transaction_id = '', $out_trade_no = '')
    {
        $app_id = $wxapp->app_id;
        $mch_id = $wxapp->mchid;
        $apikey = $wxapp->apikey;
        $client = new WxPayOrderQuery($app_id, $mch_id, $apikey);

        $params = [];
        if (!empty($transaction_id))
            $params['transaction_id'] = $transaction_id;
        if (!empty($out_trade_no))
            $params['out_trade_no'] = $out_trade_no;

        if ($params) {
            $data = $client->query($params);
        } else {
            throw new MiniPayException('查询条件 transaction_id,out_trade_no 至少一个');
        }

        return $data;
    }

    /**
     * 支付结果通知
     *
     * @param $xml
     * wxPayNotify:<xml><appid><![CDATA[wx7ba43f874a4a6516]]></appid>
     * <bank_type><![CDATA[CFT]]></bank_type>
     * <cash_fee><![CDATA[1]]></cash_fee>
     * <fee_type><![CDATA[CNY]]></fee_type>
     * <is_subscribe><![CDATA[N]]></is_subscribe>
     * <mch_id><![CDATA[1530321461]]></mch_id>
     * <nonce_str><![CDATA[10a5fb500e5f3682f89cfccac3313c41]]></nonce_str>
     * <openid><![CDATA[omaSp5YP0yyW6AAscswTMCEMXtEQ]]></openid>
     * <out_trade_no><![CDATA[20190408111342856033]]></out_trade_no>
     * <result_code><![CDATA[SUCCESS]]></result_code>
     * <return_code><![CDATA[SUCCESS]]></return_code>
     * <sign><![CDATA[6B515A619B806E7972E489FAAB4574EB]]></sign>
     * <time_end><![CDATA[20190408111434]]></time_end>
     * <total_fee>1</total_fee>
     * <trade_type><![CDATA[JSAPI]]></trade_type>
     * <transaction_id><![CDATA[4200000262201904088322185095]]></transaction_id>
     * </xml>
     * @return array
     * @throws \App\Lib\Wx\MinPay\Exception\MiniPayException
     * @throws \ErrorException
     */
    public function notify($xml)
    {

        $client = new WxPayNotifyResult();
        $data = $client->notify($xml);

        // 检验订单金额与系统内的订单的金额是否一致
        if (isset($data['result_code']) && $data['result_code'] == 'SUCCESS') {
            $out_trade_no = $data['out_trade_no'];
            $total_fee = $data['total_fee'];

            ##生产环境校验订单金额是否一致
            if (config('app.env') == 'production') {
                $order = Order::where('order_no', '=', $out_trade_no)->first();
                if ($order->paid_fee * 100 != $total_fee) {
                    throw new MiniPayException('订单金额与实际付款金额不一致');
                }
            }
            ## 支付通知结果存储
            $pay_notify = $data;
            if ($pay_notify) {
                $pay_notify_model = PayNotify::create($pay_notify);
                \Log::info(__FUNCTION__ . ':' . __LINE__ . ':' . json_encode($pay_notify_model));
            }


            // 修改订单状态
            $request = new \Illuminate\Http\Request();
            $request->offsetSet('order_no', $out_trade_no);
//            $request->offsetSet('pay_notify', $data);
            $this->orderHandler->payOrder($request);

            $resultData = ['return_code' => 'SUCCESS', 'return_msg' => 'OK'];
        } else {
            if (isset($data['return_msg']))
                throw new MiniPayException($data['return_msg']);

            throw new MiniPayException('交易异常');
        }

        return $resultData;

    }


    /**
     * @param Wxapp $wxapp
     * @param array $params
     * @return mixed
     * @throws MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function refund(Wxapp $wxapp, $params = ['transaction_id' => '', 'out_trade_no' => '', 'out_refund_no' => '', 'total_fee' => 0, 'refund_fee' => 0])
    {
        $app_id = $wxapp->app_id;
        $mch_id = $wxapp->mchid;
        $apikey = $wxapp->apikey;
        $client = new WxPayRefund($app_id, $mch_id, $apikey);

        return $client->refund($params);
    }
}