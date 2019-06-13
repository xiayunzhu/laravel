<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\InvalidRequestException;
use App\Handlers\PayHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\UnifiedOrderRequest;
use App\Lib\Response\Result;
use App\Lib\Wx\MinPay\Exception\MiniPayException;
use App\Models\Order;
use App\Models\Wxapp;
use App\Models\WxPayReport;
use Illuminate\Http\Request;


class PayController extends Controller
{
    //
    /**
     * 统一下单
     * @param UnifiedOrderRequest $request
     * @param Result $result
     * @param PayHandler $payHandler
     * @return array
     */
    public function unifiedOrder(UnifiedOrderRequest $request, Result $result, PayHandler $payHandler)
    {
        try {

            $user = auth('api')->user();
            $open_id = $user->open_id;

            //校验是否有权限 - 自己的订单才能付款
            $order_no = $request->get('order_no');
            $order = Order::where('order_no', $order_no)->first();
            if (!$order) {
                throw new InvalidRequestException("订单不存在");
            }

            //已付款或 已关闭的 抛出异常
            if ($order->pay_status || $order->close_time) {
                throw new InvalidRequestException('订单状态不正确');
            }

            //支付金额为0 的时候,直接支付成功
            if ($order->paid_fee <= 0) {
                throw new InvalidRequestException('支付成功', 100200);
            }

            //预支付订单发起
            $params = $payHandler->unify($order, $open_id);

            $result->succeed($params);

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }

    /**
     * wx支付通知地址
     * https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_7&index=8
     * @param Request $request
     * @param PayHandler $payHandler
     * @return string
     */
    public function wxPayNotify(Request $request, PayHandler $payHandler)
    {
        try {
            //获取通知的数据
            $xml = file_get_contents('php://input');//$GLOBALS['HTTP_RAW_POST_DATA'] PHP7废弃
            \Log::info(__FUNCTION__ . ',pay callback:' . $xml);
            $resultData = $payHandler->notify($xml);

            $resultData = ['return_code' => 'SUCCESS', 'return_msg' => 'OK'];
        } catch (\Exception $e) {
            \Log::info(__FUNCTION__ . ',pay callback:' . $e->getMessage());

            if ($e instanceof MiniPayException) {
                \Log::info(__FUNCTION__ . ',MiniPayException:' . $e->getMessage());
            }

            $resultData = ['return_code' => 'FAIL', 'return_msg' => $e->getMessage()];

        }

        return $this->arrayToXml($resultData);
    }

    /**
     * 构建微信需要得XML函数
     * @param $arr
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
