<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/2
 * Time: 10:56
 */

namespace App\Lib\Wx\MinPay;


use App\Lib\Wx\MinPay\Exception\MiniPayException;
use App\Lib\Wx\MinPay\Exception\SandboxException;
use Curl\Curl;
use Illuminate\Support\Facades\Cache;

class MiniPayClient
{
    /**
     * @var string
     */
    private $domain = 'https://api.mch.weixin.qq.com';

    /**
     * 接口名
     * @var string
     */
    private $method;

    /**
     * 接口请求地址 = $domain + $method
     * @var string
     */
    private $url;

    /**
     * 小程序ID
     * @var string
     */
    private $app_id;

    /**
     * 商户号
     * @var string
     */
    private $mch_id;

    /**
     * key为商户平台设置的密钥key:key设置路径：微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
     * wrKvqKJJsHkQTvKtNujDYRFmHmwwEeKC
     * @var string
     */
    private $api_key;

    /**
     * 签名
     * @var string
     */
    private $sign;

    /**
     * 签名类型:签名类型，默认为MD5，支持HMAC-SHA256和MD5。
     * 否
     * @var string
     */
    private $sign_type = 'MD5';

    /**
     * 请求参数
     * @var array
     */
    private $params = [];

    /**
     * CURL
     * @var string
     */
    private $curl;

    /**
     * 请求方式, 默认 POST;
     * @var string
     */
    private $request_method = 'POST';

    /**
     * 通知地址
     * @var \Illuminate\Config\Repository|mixed
     */
    private $notify_url;

    private $sslCertPath;

    private $sslKeyPath;

    /**
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=23_1&index=1
     * MiniPayClient constructor.
     * @throws MiniPayException
     * @throws \ErrorException
     */
    public function __construct()
    {
        $this->notify_url = config('bs.wechat.payment.default.notify_url');
        $this->curl = new Curl();
        $this->getSSLCertPath();
    }

    /**
     * @throws MiniPayException
     */
    private function getSSLCertPath()
    {
        //cert_path
        $this->sslCertPath = config('bs.wechat.payment.default.cert_path');
        if (!is_file($this->sslCertPath) || !is_readable($this->sslCertPath)) {
            //文件不存在或者不可读,抛出异常
            throw new MiniPayException('证书不存在或者不可读:' . $this->sslCertPath);
        }

        $this->sslKeyPath = config('bs.wechat.payment.default.key_path');
        if (!is_file($this->sslKeyPath) || !is_readable($this->sslKeyPath)) {
            //文件不存在或者不可读,抛出异常
            throw new MiniPayException('证书密钥不存在或者不可读');
        }
    }

    /**
     * @param string $method
     * @return string
     * @throws MiniPayException
     */
    private function makeUrl(string $method)
    {
        if (empty($this->domain))
            throw new MiniPayException('请求域名不能为空');

        $this->url = $this->domain . '/' . $method;

        \Log::info(__FUNCTION__ . ':' . $this->url);
        return $this->url;
    }

    /**
     * @return bool
     */
    public function inSandbox()
    {
        return (bool)config('bs.wechat.payment.default.sandbox');
    }

    /**
     * Wrapping an API endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     */
    protected function wrap(string $endpoint): string
    {
        return $this->inSandbox() ? "sandboxnew/{$endpoint}" : $endpoint;
    }

    /**
     * 生成随机数算法:微信支付API接口协议中包含字段nonce_str
     * 主要保证签名不可预测。我们推荐生成随机数算法如下：调用随机数函数生成，将得到的值转换为字符串
     * @return string(32)
     */
    protected function makeNonceStr()
    {
        return md5(base_convert(uniqid(), 16, 10));
    }

    /**
     * 第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串stringA。
     *
     * 特别注意以下重要规则：
     *
     * ◆ 参数名ASCII码从小到大排序（字典序）；
     * ◆ 如果参数的值为空不参与签名；
     * ◆ 参数名区分大小写；
     * ◆ 验证调用返回或微信主动通知签名时，传送的sign参数不参与签名，将生成的签名与该sign值作校验。
     * ◆ 微信接口可能增加字段，验证签名时必须支持增加的扩展字段
     * 第二步，在stringA最后拼接上key得到stringSignTemp字符串，并对stringSignTemp进行MD5运算，再将得到的字符串所有字符转换为大写，得到sign值signValue。
     * https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=4_3
     * @param array $params
     * @param string $key
     * @return string
     * @throws MiniPayException
     */
    protected function generateSign(array $params, string $key)
    {
        //参数名ASCII码从小到大排序（字典序）；
        ksort($params);

        //对参数按照key=value的格式，并按照参数名ASCII字典序排序如下
        $string = $this->ToUrlParams($params);

        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $key;

        //签名步骤三：MD5加密或者HMAC-SHA256
        if ($this->sign_type == 'MD5') {
            $string = md5($string);
        } elseif ($this->sign_type = 'HMAC-SHA256') {
            $string = hash_hmac('sha256', $string, $key);
        } else {
            throw  new MiniPayException('签名方式 sign type 必须是 MD5 或 HMAC-SHA256');
        }

        return strtoupper($string);
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $values
     * @return string
     */
    public function ToUrlParams($values = [])
    {
        $buff = "";
        foreach ($values as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @return string
     * @throws MiniPayException
     * @throws SandboxException
     */
    public function getKey(): string
    {
        if ($cache = Cache::get($this->getCacheKey())) {
            return $cache;
        }
        $params = [
            'mch_id' => $this->mch_id,
            'nonce_str' => $this->makeNonceStr(),
        ];
        $params['sign'] = $this->generateSign($params, $this->api_key);

        $response = $this->httpRequest($this->makeUrl('sandboxnew/pay/getsignkey'), $params, true);

        if ('SUCCESS' === $response['return_code']) {
            Cache::put($this->getCacheKey(), $key = $response['sandbox_signkey'], 24 * 3600);
            return $key;
        }

        throw new SandboxException($response['retmsg'] ?? $response['return_msg']);
    }

    /**
     * 沙箱密钥缓存键
     * @return string
     */
    protected function getCacheKey(): string
    {
        return 'tisu.wechat.payment.sandbox.' . md5($this->app_id . $this->mch_id);
    }

    /**
     * 发起网络请求
     * @param $endpoint
     * @param bool $useCert
     * @return mixed
     * @throws MiniPayException
     * @throws SandboxException
     */
    public function load($endpoint, $useCert = false)
    {
        //设置随机数
        $this->params['nonce_str'] = $this->makeNonceStr();

        //密钥(若是沙箱测试, 这通过 getKey 获得 api_key )
        $this->api_key = $this->inSandbox() ? $this->getKey() : $this->api_key;

        //生成签名-必须在最后执行,即之后不能再增加或修改参数
        $this->params['sign'] = $this->generateSign($this->params, $this->api_key);

        //确认接口方法名(沙箱测试自动转化为测试接口)
        $method = $this->wrap($endpoint);

        return $this->httpRequest($this->makeUrl($method), $this->params, $useCert);
    }


    /**
     * @param $url
     * @param $params
     * @param $useCert
     * @return mixed
     * @throws MiniPayException
     */
    private function httpRequest($url, $params, $useCert = false)
    {
        \Log::info(__FUNCTION__ . ':' . $url . PHP_EOL . ',params:' . print_r($params, true));
        return $this->xmlToArray($this->postXmlCurl($url, $this->arrayToXml($params), $useCert));
    }

    /**
     * 使用Xml发起请求
     * @param $url
     * @param $xml
     * @param bool $useCert 是否需要使用证书
     * @param int $second
     * @return mixed
     * @throws MiniPayException
     */
    private function postXmlCurl($url, $xml, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);


        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);


        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //证书文件请放入服务器的非web目录下
        if ($useCert == true) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->sslCertPath);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->sslKeyPath);
        }


        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new MiniPayException("curl出错，错误码:$error");
        }
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

    /**
     * xml转换成数组
     * @param $xml
     * @return mixed
     * @throws MiniPayException
     */
    public function xmlToArray($xml)
    {
        if (!$xml) {
            throw new MiniPayException("xml数据异常！");
        }

        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $val = json_decode(json_encode($xmlstring), true);

        return $val;
    }


    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * @param string $app_id
     */
    public function setAppId(string $app_id)
    {
        $this->app_id = $app_id;
    }


    /**
     * @return string
     */
    public function getMchId(): string
    {
        return $this->mch_id;
    }

    /**
     * @param string $mch_id
     */
    public function setMchId(string $mch_id)
    {
        $this->mch_id = $mch_id;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     */
    public function setApiKey(string $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return array
     */
    public
    function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public
    function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public
    function getCurl(): string
    {
        return $this->curl;
    }

    /**
     * @param string $curl
     */
    public
    function setCurl(string $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return string
     */
    public
    function getRequestMethod(): string
    {
        return $this->request_method;
    }

    /**
     * @param string $request_method
     */
    public
    function setRequestMethod(string $request_method)
    {
        $this->request_method = $request_method;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public
    function getNotifyUrl()
    {
        return $this->notify_url;
    }

    /**
     * @param \Illuminate\Config\Repository|mixed $notify_url
     */
    public
    function setNotifyUrl($notify_url)
    {
        $this->notify_url = $notify_url;
    }


}