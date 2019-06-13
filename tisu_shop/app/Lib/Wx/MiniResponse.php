<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/23
 * Time: 19:07
 */

namespace App\Lib\Wx;


class MiniResponse
{
    /**
     * 用户唯一标识
     * @var string
     */
    private $openid;

    /**
     * 会话密钥
     * @var string
     */
    private $session_key;

    /**
     * 用户在开放平台的唯一标识符,https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/union-id.html
     * @var string
     */
    private $unionid;

    /**
     * 错误码:
     * @var integer
     */
    private $errcode;

    /**
     * 错误信息
     * @var string
     */
    private $errmsg;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'openid' => $this->openid,
            'session_key' => $this->session_key,
            'unionid' => $this->unionid,
            'errcode' => $this->errcode,
            'errmsg' => $this->errmsg,
        ];
    }

    /**
     * @return string
     */
    public function getOpenid(): string
    {
        return $this->openid;
    }

    /**
     * @param string $openid
     */
    public function setOpenid(string $openid)
    {
        $this->openid = $openid;
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->session_key;
    }

    /**
     * @param string $session_key
     */
    public function setSessionKey(string $session_key)
    {
        $this->session_key = $session_key;
    }

    /**
     * @return string
     */
    public function getUnionid(): string
    {
        return $this->unionid;
    }

    /**
     * @param string $unionid
     */
    public function setUnionid(string $unionid)
    {
        $this->unionid = $unionid;
    }

    /**
     * @return int
     */
    public function getErrcode(): int
    {
        return $this->errcode;
    }

    /**
     * @param int $errcode
     */
    public function setErrcode(int $errcode)
    {
        $this->errcode = $errcode;
    }

    /**
     * @return string
     */
    public function getErrmsg(): string
    {
        return $this->errmsg;
    }

    /**
     * @param string $errmsg
     */
    public function setErrmsg(string $errmsg)
    {
        $this->errmsg = $errmsg;
    }


}