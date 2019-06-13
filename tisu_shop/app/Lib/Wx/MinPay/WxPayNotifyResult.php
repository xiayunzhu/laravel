<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/4
 * Time: 12:48
 */

namespace App\Lib\Wx\MinPay;


use App\Lib\Wx\MinPay\Exception\MiniPayException;
use App\Models\Wxapp;

class WxPayNotifyResult extends MiniPayClient
{
    /**
     * https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_7&index=8
     * @param $xml
     * @return mixed
     * @throws Exception\MiniPayException
     */
    public function notify($xml)
    {
        $data = $this->xmlToArray($xml);
//        \Log::info(__CLASS__ . '::' . __FUNCTION__ . json_encode($data));
        #### 支付通知-只有接受到回调信息,才能确认是哪个app
        //初始化 wxapp
        $appid = isset($data['appid']) ? $data['appid'] : null;
        $this->init($appid);

        //签名检验
        $this->checkSign($data);

        return $data;
    }

    /**
     * 初始化APP
     * @param $appid
     */
    public function init($appid)
    {
        $this->setAppId($appid);

        // 获取对应的 wxapp设置信息
        $wxapp = Wxapp::where('app_id', $appid)->first();
        \Log::info(__CLASS__ . '::' . __FUNCTION__ . json_encode($wxapp));
        $this->setApiKey($wxapp->apikey);
        $this->setMchId($wxapp->mchid);
    }

    /**
     * @param $data
     * @return bool
     * @throws MiniPayException
     */
    public function checkSign($data)
    {
        $sign = $this->generateSign($data, $this->getApiKey());
        if ($sign != $data['sign'])
            throw new MiniPayException("签名错误！");

        return true;
    }

}