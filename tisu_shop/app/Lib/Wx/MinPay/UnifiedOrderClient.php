<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/1
 * Time: 17:25
 */

namespace App\Lib\Wx\MinPay;


use App\Lib\Wx\MinPay\Exception\MiniPayException;

class UnifiedOrderClient extends MiniPayClient
{
    // 应用场景:商户在小程序中先调用该接口在微信支付服务后台生成预支付交易单，返回正确的预支付交易后调起支付。

    private $unifyData;

    private $endpoint = 'pay/unifiedorder';


    /**
     * UnifiedOrderClient constructor.
     * @param string $appid 小程序ID
     * @param string $mch_id 商户号
     * @param string $api_key API安全密钥
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
     * 支付信息
     * @param array $params
     * @return mixed
     * @throws Exception\SandboxException
     * @throws MiniPayException
     */
    public function unify(array $params)
    {
        //必要参数校验
        $this->checkParams($params);
        if ($is_sandbox = (bool)config('bs.wechat.payment.default.sandbox')) {
            $app_id = 'wx7ba43f874a4a6516';
            $mch_id = '1530321461';
            $api_key = 'wrKvqKJJsHkQTvKtNujDYRFmHmwwEeKC';

            $this->setAppId($app_id);
            $this->setMchId($mch_id);
            $this->setApiKey($api_key);
            $params['total_fee'] = 101;//101 沙箱支付金额(5000)无效，请检查需要验收的case: 金额只能是 1.01 和 1.0.2
        }

        $params['spbill_create_ip'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0';//服务器IP地址
        $params['trade_type'] = 'JSAPI';//必须为JSAPI
        $params['notify_url'] = $params['notify_url'] ?? $this->getNotifyUrl();
        $params['appid'] = $this->getAppId();
        $params['mch_id'] = $this->getMchId();

        $this->setParams($params);
        $this->setRequestMethod('POST');
        $resultUnify = $this->load($this->endpoint);

        $this->unifyData = $resultUnify;

        if (isset($resultUnify['return_code'])
            && $resultUnify['return_code'] == 'SUCCESS'
            && isset($resultUnify['result_code'])
            && $resultUnify['result_code'] == 'SUCCESS') {

            // 二次签名的参数必须与下面相同
            return $this->paymentData($resultUnify);

        } else {
            \Log::info(__FUNCTION__ . ',resultUnify:' . json_encode($resultUnify));
            $return_msg = isset($resultUnify['return_msg']) ? $resultUnify['return_msg'] : '统一下单失败';
            $err_code = isset($resultUnify['err_code']) ? $resultUnify['err_code'] : '';
            $err_code_des = isset($resultUnify['err_code_des']) ? urldecode($resultUnify['err_code_des']) : '';
            throw new MiniPayException($return_msg . '-' . $err_code . '-' . $err_code_des);
        }

    }

    /**
     * @param $resultUnify
     * @return array
     */
    public function paymentData($resultUnify)
    {
        \Log::info(__LINE__ . ',:' . __FUNCTION__ . json_encode($resultUnify));
        $params = [
            'appId' => $this->getAppId(),
            'timeStamp' => time(),
            'nonceStr' => $resultUnify['nonce_str'],
            'package' => 'prepay_id=' . $resultUnify['prepay_id'],
            'signType' => 'MD5'
        ];

        // config('wechat.payment.default.key')为商户的key
        $params['paySign'] = $this->generate_sign($params, $this->getApiKey());
        return $params;
    }

    /**
     * Generate a signature.
     *
     * @param array $attributes
     * @param string $key
     * @param string $encryptMethod
     *
     * @return string
     */
    function generate_sign(array $attributes, $key, $encryptMethod = 'md5')
    {
        ksort($attributes);

        $attributes['key'] = $key;

        return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
    }

    /**
     * @param $params
     * @throws MiniPayException
     */
    public function checkParams($params)
    {
        $fields = ['body', 'out_trade_no', 'openid', 'total_fee'];
        foreach ($fields as $field) {
            if (empty($params[$field]))
                throw new MiniPayException('参数错误:' . $field . ' 必传且不能为空');
        }
    }

    /**
     * @return mixed
     */
    public function getUnifyData()
    {
        return $this->unifyData;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }


}