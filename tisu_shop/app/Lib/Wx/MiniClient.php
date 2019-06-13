<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/23
 * Time: 19:00
 */

namespace App\Lib\Wx;


use App\Exceptions\MiniException;
use Curl\Curl;

class MiniClient
{
    /**
     * 小程序 appId
     * @var string
     */
    private $appId;
    /**
     * 小程序 appSecret
     * @var string
     */
    private $secret;
    /**
     * 登录时获取的 code
     * @var string
     */
    private $js_code;

    /**
     * 授权类型，此处只需填写 authorization_code
     * @var string
     */
    private $grant_type = 'authorization_code';

    /**
     * @var string
     */
    private $request_method = 'GET';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * 请求地址
     * @var string
     */
    private $code2session_url;
    /**
     *
     * @var string
     */
    private $sessionKey;

    /**
     * @var MiniResponse
     */
    private $miniResponse;

    /**
     * MiniClient constructor.
     * @param MiniResponse $miniResponse
     * @throws \ErrorException
     */
    public function __construct(MiniResponse $miniResponse)
    {
        $this->code2session_url = config('mini.code2session_url', '');
        $this->miniResponse = $miniResponse;
        $this->curl = new Curl();
    }

    /**
     * Created by vicleos
     * @param $code
     * @return mixed
     * @throws MiniException
     */
    public function getLoginInfo($code)
    {
        $this->js_code = $code;
        return $this->authCodeAndCode2session();
    }

    /**
     * 根据 code 获取 session_key 等相关信息
     * @return array|mixed
     * @throws MiniException
     */
    public function authCodeAndCode2session()
    {
        $code2session_url = sprintf($this->code2session_url, $this->appId, $this->secret, $this->js_code);

        $userInfo = file_get_contents($code2session_url);
        if ($userInfo && is_json($userInfo)) {
            $userInfo = json_decode($userInfo, true);
        }

        if (!isset($userInfo['session_key'])) {
            throw new MiniException('获取 session_key 失败');
        }

        if (!isset($userInfo['openid'])) {
            throw new MiniException('获取 openid 失败');
        }

        $this->sessionKey = $userInfo['session_key'];

        return ['openid' => $userInfo['openid'], 'session_key' => $userInfo['session_key']];
    }

    /**
     * @param $encryptedData
     * @param $iv
     * @return array|string
     */
    public function getUserInfo($encryptedData, $iv)
    {
        $pc = new MiniDataCrypt($this->appId, $this->sessionKey);
        $decodeData = "";
        $errCode = $pc->decryptData($encryptedData, $iv, $decodeData);
        if ($errCode != 0) {
            return [
                'code' => 10001,
                'message' => 'encryptedData 解密失败'
            ];
        }
        return $decodeData;
    }


    /**
     * 发起请求
     * @param $url
     * @return mixed
     */
    public function httpRequest($url)
    {
        try {
            $res = $this->curl->get($url);
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
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getJsCode(): string
    {
        return $this->js_code;
    }

    /**
     * @param string $js_code
     */
    public function setJsCode(string $js_code)
    {
        $this->js_code = $js_code;
    }

    /**
     * @return MiniResponse
     */
    public function getMiniResponse(): MiniResponse
    {
        return $this->miniResponse;
    }

    /**
     * @param MiniResponse $miniResponse
     */
    public function setMiniResponse(MiniResponse $miniResponse)
    {
        $this->miniResponse = $miniResponse;
    }


}