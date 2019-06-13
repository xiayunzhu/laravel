<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/9
 * Time: 16:09
 */

namespace App\Lib\Wln;


use Curl\Curl;

class WLnOpenClient
{
    private $domain;
    private $method;
    private $url;//url = domain + method
    private $app_key;
    private $app_secret;
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

    private $page = 1;

    private $limit = 20;

    /**
     * 请求方式, 默认 POST;
     * @var string
     */
    private $request_method = 'POST';

    public function __construct()
    {
        $this->domain = config('bs.wln.open.domain');
        $this->app_key = config('bs.wln.open.app_key');
        $this->app_secret = config('bs.wln.open.app_secret');
    }

    /**
     * @return $this
     */
    public function makeUrl()
    {
        $this->url = $this->domain . '/' . $this->method;

        return $this;
    }

    /**
     * 签名
     * @param array $params
     * @param string $app_secret
     * @return string
     */
    function generateMd5Sign($params = array(), $app_secret = '')
    {
        if (isset($params['_sign']) || isset($params['_sign_kind']))
            unset($params['_sign'], $params['_sign_kind']);

        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . '=' . urlencode(strtoupper($v));
        }

        $string = $app_secret . implode('&', $tmps) . $app_secret;
        return strtoupper(md5($string));
    }

    /**
     * 获取-请求参数签名值
     * @return $this
     */
    public function makeSign()
    {
        $this->params['_sign'] = $this->generateMd5Sign($this->params, $this->app_secret);

        return $this;
    }

    /**
     * 追加条件
     * @param $kvArr
     */
    public function appendParams($kvArr)
    {
        $this->params = array_merge($this->params, $kvArr);
    }

    /**
     * 默认条件设置
     * @return $this
     */
    public function defaultParams()
    {
        $defaultParams = [
            '_app' => $this->app_key,
            '_sign' => '',
            '_s' => '',
            '_t' => time(),
            '_sign_kind' => 'md5', // 默认md5，签名生成方式，不参与签名生成，官方文档里是必填，实际对接测试过程中可以不填
        ];

        $this->params = array_merge($this->params, $defaultParams);
        return $this;
    }

    /**
     * 发起请求
     * @return mixed
     */
    public function httpRequest()
    {
        try {
            $this->curl = new Curl();

            if (strtoupper($this->request_method) == 'POST') {
                $res = $this->curl->post($this->url, $this->params);
            } else {
                $res = $this->curl->get($this->url, $this->params);
            }

            $json_data = $res->response;

            if (!$json_data) {
                return false;
            }

            return is_json($json_data) ? json_decode($json_data, true) : $json_data;
        } catch (\Exception $exception) {

            return ['message' => $exception->getMessage()];
        }
    }

    /**
     * @return mixed
     */
    public function load()
    {
        $this->defaultParams();
        $this->makeSign();
        $this->makeUrl();

        return $this->httpRequest();
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->request_method;
    }

    /**
     * @param string $request_method
     */
    public function setRequestMethod(string $request_method)
    {
        $this->request_method = $request_method;
    }


}