<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/4
 * Time: 15:35
 */

namespace App\Lib\Wx\MinPay;


use App\Lib\Wx\MinPay\Exception\MiniPayException;

class WxPayOrderQuery extends MiniPayClient
{

    /**
     * UnifiedOrderClient constructor.
     * @param string $appid 小程序ID
     * @param string $mch_id 商户号
     * @param string $api_key
     * @throws MiniPayException
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
     * wx03194253202204564546ad792339455217
     * @param array $params ['transaction_id' => '微信的订单号，优先使用', 'out_trade_no' => '']
     * @return mixed
     * @throws Exception\MiniPayException
     * @throws Exception\SandboxException
     */
    public function query($params = ['transaction_id' => '', 'out_trade_no' => ''])
    {

        $params['appid'] = $this->getAppId();
        $params['mch_id'] = $this->getMchId();

        $this->setParams($params);
        $this->setRequestMethod('POST');

        return $this->load('pay/orderquery');
    }

}