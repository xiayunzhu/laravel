<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 11:22
 */

namespace App\Http\Controllers\Api;


use App\Handlers\ShopHandler;
use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\ChangeRequest;
use App\Lib\Response\Result;
use App\Lib\Tools\VerificationCode;
use App\Models\ShopManager;
use App\Models\User;
use Illuminate\Http\Request;


class UsersController extends Controller
{
    private $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**用户信息
     *
     * @param Result $result
     * @return array
     */
    public function info(Result $result)
    {
        $user_id = \auth('api')->id();
        if ($user_id) {
            $user = User::find($user_id);
            $result->succeed($user);
        } else {
            $result->failed('请登录');
        }
        return $result->toArray();
    }

    /**
     * @param Request $request
     * @param Result $result
     * @param VerificationCode $verificationCode
     * @return array|\Ml\Response\Result
     */
    public function sms(Request $request, Result $result, VerificationCode $verificationCode)
    {

        try {
            $phone = $request->get('phone');

            if (!is_phone_number($phone)) {
                return $result->failed('必须是正确的手机号码');
            }

            // 发送短信验证码
            $templateParam = ['code' => rand(100000, 999999)];
            $verificationCode->setTemplateType('change_info');
            $result = $verificationCode->sendCode($phone, $templateParam);

            session(['sms_code' => $templateParam]);
            return $result->toArray();
        } catch (\Exception $exception) {

            if ($exception instanceof LoginException || $exception instanceof VerificationCodeException)
                return $result->failed($exception->getMessage())->toArray();

            return $result->failed('系统繁忙,请稍等')->toArray();
        }
    }

    /**
     * @param ChangeRequest $request
     * @param Result $result
     * @param VerificationCode $code
     * @return bool|\Illuminate\Http\JsonResponse|\Ml\Response\Result
     */
    public function phoneChange(ChangeRequest $request, Result $result, VerificationCode $code)
    {
        try {
            //   $data = $auth->InfoByVfCode($request, $result, $code);
            $phone = $request->get('phone');
            $v_code = $request->get('v_code');
            $code->checkCode($phone, $v_code);
            $user_id = \auth('api')->id();
            if ($user_id) {

                $model = $this->userHandler->update($user_id, $request);
                $result->succeed($model);
            } else {

                $result->failed('该ID为空！');
            }


        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return response()->json($result->toArray());

    }

    public function update(Request $request, Result $result)
    {
        $user_id = \auth('api')->id();
        if ($user_id) {
            try {
                $model = $this->userHandler->update($user_id, $request);
                $result->succeed($model);
            } catch (\Exception $exception) {
                $result->failed($exception->getMessage());
            }
        } else {
            $result->failed('该ID为空！');
        }

        return response()->json($result->toArray());
    }

    /**
     * @param Request $request
     * @param Result $result
     * @param ShopHandler $shopHandler
     * @return array
     */
    public function shopList(Request $request, Result $result, ShopHandler $shopHandler)
    {
        $user_id = \auth('api')->id();
        if ($user_id) {
            try {
                $data = $shopHandler->shopList($user_id, $request);
                $result->succeed($data);
            } catch (\Exception $exception) {
                $result->failed($exception->getMessage());
            }

        } else {
            $result->failed('请登录');
        }
        return $result->toArray();
    }
}