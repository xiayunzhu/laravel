<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/27
 * Time: 14:43
 */

namespace App\Lib\Tools;


use App\Exceptions\VerificationCodeException;
use App\Lib\Ali\AlibabaClient;
use App\Lib\Response\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VerificationCode
{
    /**
     * 阿里客户端
     * @var AlibabaClient
     */
    private $client;

    /**
     * 验证码 有效期 默认 10(m)
     * @var int
     */
    private $life_cycle_minutes = 10;
    /**
     * 发送时间间隔 默认 60(s)
     * @var int
     */
    private $send_interval = 60;

    private $template_type;

    public function __construct(AlibabaClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $phoneNumber
     * @param array $templateParam
     * @return \Ml\Response\Result
     * @throws VerificationCodeException
     */
    public function sendCode(string $phoneNumber, array $templateParam)
    {

        $this->client->setPhoneNumbers($phoneNumber);
        $this->client->setTemplateParam(json_encode($templateParam));
//        $this->client->setTemplateCode(config('ali.templateCode.login_certification.code'));
        $this->matchTemplate();
        // 校验一分钟内是否已发送过验证码
        $expiresAt = Carbon::now()->addMinutes($this->life_cycle_minutes);

        // 缓存key
        $cacheKey = __CLASS__ . '_' . $phoneNumber;
        $cacheValue = Cache::get($cacheKey);
        if (!empty($cacheValue)) {
            ## 限制发送间隔
            if (time() - (int)$cacheValue['cache_create_time'] < $this->send_interval) {
                throw new VerificationCodeException(ceil($this->send_interval) . '分钟内不能重复发送,请稍等');
            }
        }

        //创建时间
        $cacheValue = $templateParam;
        $cacheValue['cache_create_time'] = time();

        if (config('app.env') == 'production') {
            $result = $this->client->sms();
            if ($result->isSuccess()) {
                Cache::put($cacheKey, $cacheValue, $expiresAt);
            }
            return $result;
        } else {
//            Cache::put($cacheKey, $cacheValue, $expiresAt);
            $result = new Result();
            $result->succeed(["Message" => "OK", "RequestId" => 'TEST' . create_object_id(), 'BizId' => date('YmdHis'), 'Code' => 'OK']);
            return $result;
        }

    }

    /**
     * 获取验证码缓存信息
     * @param string $phoneNumber
     * @return mixed
     * @throws VerificationCodeException
     */
    private function getCode(string $phoneNumber)
    {
        // 缓存key
        $cacheKey = __CLASS__ . '_' . $phoneNumber;
        $cacheValue = Cache::get($cacheKey);
        $code = isset($cacheValue['code']) ? $cacheValue['code'] : null;
        if (empty($code)) {
            throw new VerificationCodeException('验证码已失效!!!');
        }

        //删除缓存
        Cache::forget($cacheKey);

        return $code;
    }

    /**
     * @param string $phoneNumber
     * @param $v_code
     * @return bool
     * @throws VerificationCodeException
     */
    public function checkCode(string $phoneNumber, $v_code)
    {
        // 非生产环境-直接认证通过
        if (config('app.env') !== 'production') {
            return true;
        }

        $code = $this->getCode($phoneNumber);
        if ((string)$v_code !== (string)$code) {
            throw new VerificationCodeException('验证码错误');
        }

        return true;
    }

    /**
     * 匹配短信模板
     * 默认登录验证码的短信模板-login_certification
     */
    private function matchTemplate()
    {
        $tplType = $this->template_type ?? 'login_certification';
        $this->client->setTemplateCode(config("ali.templateTypes.{$tplType}.code"));
    }

    /**
     * @return mixed
     */
    public function getTemplateType()
    {
        return $this->template_type;
    }

    /**
     * @param mixed $template_type
     */
    public function setTemplateType($template_type)
    {
        $this->template_type = $template_type;
    }

}