<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/14
 * Time: 18:32
 */

namespace App\Lib\Wln;


use Curl\Curl;

class WlnB2cClient
{
    private $domain;
    private $method;
    private $url;//url = domain + method
    private $app_key;
    private $app_secret;
    private $app_format = 'json';
    private $hash_type = 'md5';
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
     * 页码
     * @var int
     */
    private $page = 1;
    /**
     * 分页大小
     * @var int
     */
    private $limit = 20;

    /**
     * 请求方式, 默认 POST;
     * @var string
     */
    private $request_method = 'POST';

    public function __construct()
    {
        $this->domain = config('bs.wln.b2c.domain');
        $this->app_key = config('bs.wln.b2c.app_key');
        $this->app_secret = config('bs.wln.b2c.app_secret');
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
     * @param array $params
     * @param string $app_secret
     * @return string
     */
    function generateMd5Sign($params = array(), $app_secret = '')
    {
        if (isset($params['sign']))
            unset($params['sign']);

        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = $app_secret . implode('', $tmps) . $app_secret;
        return strtoupper(md5($string));
    }

    /**
     * 获取-请求参数签名值
     * @return $this
     */
    public function makeSign()
    {
        $this->params['sign'] = $this->generateMd5Sign($this->params, $this->app_secret);

        return $this;
    }

    /**
     * 默认条件设置
     * @return $this
     */
    public function defaultParams()
    {
        $defaultParams = [
            'app_key' => $this->app_key,
            'format' => $this->app_format,
            'timestamp' => (int)floor(microtime(true) * 1000),
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
    protected function load()
    {
        $this->defaultParams();
        $this->makeSign();
        $this->makeUrl();

        return $this->httpRequest();
    }

    /**
     * 追加条件
     * @param $kvArr
     */
    protected function appendParams($kvArr)
    {
        $this->params = array_merge($this->params, $kvArr);
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