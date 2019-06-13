<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/10
 * Time: 20:26
 */

namespace App\Lib\Wx\MinPay;


use App\Lib\Wx\MinPay\Exception\MiniPayException;
use Illuminate\Support\Facades\Validator;

class WxPayRefund extends MiniPayClient
{
    private $endpoint = 'secapi/pay/refund';

    /**
     * UnifiedOrderClient constructor.
     * @param string $appid 小程序ID
     * @param string $mch_id 商户号
     * @param string $api_key
     * @throws Exception\MiniPayException
     * @throws \ErrorException
     */
    public function __construct(string $appid, string $mch_id, string $api_key)
    {
        //接口:统一下单
//        $this->setMethod('pay/unifiedorder');
        $this->setAppId($appid);
        $this->setMchId($mch_id);
        $this->setApiKey($api_key);
        $this->setRequestMethod('POST');

        parent::__construct();
    }

    /**
     * @param array $params
     * @return mixed
     * @throws Exception\SandboxException
     * @throws MiniPayException
     */
    public function refund(array $params = ['transaction_id' => '', 'out_trade_no' => '', 'out_refund_no' => '', 'total_fee' => 0, 'refund_fee' => 0])
    {
        $params['appid'] = $this->getAppId();
        $params['mch_id'] = $this->getMchId();
        $this->setParams($params);
        $this->setRequestMethod('POST');

        return $this->load($this->endpoint, true);
    }

    /**
     * @param $params
     * @return bool
     * @throws MiniPayException
     */
    public function checkParams($params)
    {
        $rules = [
            'appid' => 'required',
            'mch_id' => 'required',
            'transaction_id' => 'required_without:out_trade_no|string|max:32',
            'out_trade_no' => 'required_without:transaction_id|string|max:32',
            'out_refund_no' => 'required|string|max:64',
            'total_fee' => 'required|integer',
            'refund_fee' => 'required|integer',
        ];

        $messages = [
            'refund_fee.required' => '退款金额必填'
        ];
        $v = Validator::make($params, $rules, $messages);
        if ($v->fails()) {
            throw new MiniPayException($v->messages()->first());
        }

        return true;
    }


}