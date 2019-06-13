<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/27
 * Time: 11:17
 */

namespace App\Lib\Ali;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Exceptions\LoginException;
use App\Lib\Response\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AlibabaClient
{
    private $accessKeyId;
    private $accessSecret;
    private $regionId = 'cn-hangzhou';
    private $signName;

    /**
     * 接收短信的手机号码。
     * 格式：
     * 国内短信：11位手机号码，例如15951955195。
     * 国际/港澳台消息：国际区号+号码，例如85200000000。
     * 支持对多个手机号码发送短信，手机号码之间以英文逗号（,）分隔。上限为1000个手机号码。批量调用相对于单条调用及时性稍有延迟。
     * @var string
     */
    private $phoneNumbers;
    /**
     * 短信模板ID。请在控制台模板管理页面模板CODE一列查看。 SMS_161500422
     * 是
     * @var string
     */
    private $templateCode;

    /**
     * 短信模板变量对应的实际值，JSON格式。
     * @var string
     */
    private $templateParam;

    /**
     * 外部流水扩展字段。
     * @var string
     */
    private $outId;


    public function __construct()
    {
        $this->accessKeyId = config('ali.accessKeyId');
        $this->accessSecret = config('ali.accessSecret');
        $this->signName = config('ali.signName');

        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessSecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }


    /**
     *
     * {
     * "Message":"OK",
     * "RequestId":"2184201F-BFB3-446B-B1F2-C746B7BF0657",
     * "BizId":"197703245997295588^0",
     * "Code":"OK"
     * }
     * @return \Ml\Response\Result
     */
    public function sms()
    {
        $result = new  Result();
        try {
            $smsResult = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $this->phoneNumbers,
                        'SignName' => $this->signName,
                        'TemplateCode' => $this->templateCode,
                        'TemplateParam' => $this->templateParam,
                        'OutId' => $this->outId,
                    ],
                ])
                ->request();

            return $result->succeed($smsResult->toArray());

        } catch (ClientException $exception) {
            return $result->failed($exception->getErrorMessage());
        } catch (ServerException $exception) {
            return $result->failed($exception->getErrorMessage());
        }
    }

    /**
     * 登录验证码
     * @param $phoneNumber
     * @param array $templateParam
     * @return \Ml\Response\Result
     * @throws LoginException
     */
    public function loginVerificationSms($phoneNumber, $templateParam = [])
    {
        $this->phoneNumbers = $phoneNumber;
        $this->templateParam = json_encode($templateParam);
        $this->templateCode = config('ali.templateCode.login_certification.code');

        // 校验一分钟内是否已发送过验证码
        $expiresAt = Carbon::now()->addMinutes(1);
        // 缓存key 方法名 + 手机号
        $cacheKey = __CLASS__ . '_' . __FUNCTION__ . '_' . $this->phoneNumbers;
        $cacheValue = Cache::get($cacheKey);
        if (!empty($cacheValue)) {
            throw  new LoginException('一分钟内不能重复发送,请稍等');
        }

        $result = $this->sms();
        if ($result->isSuccess()) {
            Cache::put($cacheKey, $this->templateParam, $expiresAt);
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getTemplateCode()
    {
        return $this->templateCode;
    }

    /**
     * @param mixed $templateCode
     */
    public function setTemplateCode($templateCode)
    {
        $this->templateCode = $templateCode;
    }

    /**
     * @return string
     */
    public function getOutId(): string
    {
        return $this->outId;
    }

    /**
     * @param string $outId
     */
    public function setOutId(string $outId)
    {
        $this->outId = $outId;
    }

    /**
     * @return string
     */
    public function getPhoneNumbers(): string
    {
        return $this->phoneNumbers;
    }

    /**
     * @param string $phoneNumbers
     */
    public function setPhoneNumbers(string $phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
    }

    /**
     * @return string
     */
    public function getTemplateParam(): string
    {
        return $this->templateParam;
    }

    /**
     * @param string $templateParam
     */
    public function setTemplateParam(string $templateParam)
    {
        $this->templateParam = $templateParam;
    }


}