<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LoginException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Login\loginByVfCodeRequest;
use App\Lib\Response\Result;
use App\Lib\Tools\VerificationCode;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group 用户认证(user authentication)
 * author:JJG
 * review_at:2019-05-11
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'sms', 'verification']]);
    }

    /**
     * 发送验证码(api.auth.verification)
     * @queryParam phone required 账号 Example: 15869021868
     * @param Request $request
     * @param Result $result
     * @param VerificationCode $verificationCode
     * @return array
     */
    public function verification(Request $request, Result $result, VerificationCode $verificationCode)
    {
        try {
            $phone = $request->get('phone');
            if (!is_phone_number($phone)) {
                $result->failed('必须是正确的手机号码');
            }
            //判断是否手机用户
            $this->isPhoneUser($phone);

            // 发送短信验证码
            $templateParam = ['code' => rand(100000, 999999)];
            $result = $verificationCode->sendCode($phone, $templateParam);

            return $result->toArray();
        } catch (\Exception $exception) {

            if ($exception instanceof LoginException || $exception instanceof VerificationCodeException)
                return $result->failed($exception->getMessage())->toArray();

            return $result->failed('系统繁忙,请稍等')->toArray();
        }
    }

    /**
     * @param $phone
     * @return mixed
     * @throws LoginException
     */
    protected function isPhoneUser($phone)
    {
        $user = User::where('phone', $phone)->first();
        if ($user)
            return $user;

        throw new LoginException('手机用户不存在');
    }

    /**
     * 手机登录(api.auth.login)
     * @queryParam phone required 账号 Example: 15869021868
     * @queryParam v_code required 验证码 Example: 123456
     * @queryParam __debugger 模拟登录账号. Example: 1
     * @param loginByVfCodeRequest $request
     * @param Result $result
     * @param VerificationCode $verificationCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(loginByVfCodeRequest $request, Result $result, VerificationCode $verificationCode)
    {
        try {
            $phone = $request->get('phone');
            $v_code = $request->get('v_code');

            $bool = is_phone_number($phone);
            if (!$bool) {
                throw new LoginException('手机号码格式不正确');
            }

            $user = User::where('phone', $phone)->first();
            if (!$user) {
                throw new LoginException('用户不存在');
            }

            //校验验证码
            $verificationCode->checkCode($phone, $v_code);

            if (!$token = auth('api')->login($user)) {
                throw new LoginException('账号异常,无法登录');
            }

            return $this->respondWithToken($token);
        } catch (\Exception $exception) {

            if ($exception instanceof LoginException || $exception instanceof VerificationCodeException)
                $result->failed($exception->getMessage());
            else
                $result->failed('系统繁忙,请稍后重试');
            $result->failed($exception->getMessage());
            return response()->json($result->toArray(), 401);
        }

    }

    /**
     * Get the login username to be used by the controller.
     * 判断账号类型 email 或者 username
     * @return string
     */
    protected function username()
    {
        return request('login_field') ?: 'email';
    }

    /**
     * 我的(api.auth.me)
     *
     * Get the authenticated User.
     *
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Result $result)
    {
        $user = auth('api')->user();

        $result->succeed($user);

        return response()->json($result->toArray());
    }

    /**
     * 退出登录(api.auth.logout)
     * Log the user out (Invalidate the token).
     * @queryParam __debugger 模拟登录账号. Example: 1
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Result $result)
    {
        auth('api')->logout();

        $result->succeed(['message' => '退出成功']);

        return response()->json($result->toArray());
    }

    /**
     * 刷新token(api.auth.refresh)
     * Refresh a token.
     * @queryParam __debugger 模拟登录账号. Example: 1
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $result = new Result();
        $result->succeed([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);

        return response()->json($result->toArray());
    }
}
