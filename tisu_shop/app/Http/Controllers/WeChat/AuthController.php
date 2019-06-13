<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\MiniException;
use App\Handlers\BuyerHandler;
use App\Handlers\WxappHandler;
use App\Http\Requests\WeChat\Auth\AuthorizationRequest;
use App\Http\Requests\WeChat\Auth\LoginRequest;
use App\Lib\Response\Result;
use App\Lib\Wx\MiniClient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $this->middleware('auth:api', ['except' => ['login', 'authorization']]);
    }

    /**
     * @param AuthorizationRequest $request
     * @param Result $result
     * @param MiniClient $miniClient
     * @param WxappHandler $wxappHandler
     * @param BuyerHandler $buyerHandler
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function authorization(AuthorizationRequest $request, Result $result, MiniClient $miniClient, WxappHandler $wxappHandler, BuyerHandler $buyerHandler)
    {
        try {

            $code = $request->get('code');
            $iv = $request->get('iv');
            $encryptedData = $request->get('encryptedData');
            $app_id = $request->get('app_id');
            $wxapp = $wxappHandler->getByAppId($app_id);

            ## 获取用户信息
            $miniClient->setJsCode($code);
            $miniClient->setAppId($wxapp->app_id);
            $miniClient->setSecret($wxapp->app_secret);
            $wxuser = $miniClient->authCodeAndCode2session();//先获取到 session_key
            $wxInfo = $miniClient->getUserInfo($encryptedData, $iv);//再解析用户信息
            if (is_string($wxInfo)) {
                $wxInfo = is_json($wxInfo) ? json_decode($wxInfo, true) : []; // 得到第一个元素
            } else {
                $result->failed($wxInfo);
                return $result->toArray();
            }

            ## 获取openID
            if (!$openId = isset($wxInfo['openId']) ? $wxInfo['openId'] : '') {
                $result->failed('openId 识别识别');
                return $result->toArray();
            };

            ## 注册并登录
            $user = User::where('open_id', $openId)->first();
            \DB::transaction(function () use ($openId, $buyerHandler, $wxapp, $wxInfo, &$user) {
                if (!$user) {
                    $create = [
                        'open_id' => $openId,
                        'name' => isset($wxInfo['nickName']) ? $wxInfo['nickName'] : '',
                        'sex' => isset($wxInfo['gender']) && $wxInfo['gender'] == 1 ? '1' : '0',
                        'avatar' => isset($wxInfo['avatarUrl']) ? $wxInfo['avatarUrl'] : '',
                        'user_type' => User::USER_TYPE_BUYER
                    ];

                    $user = User::create($create);
                    if (!$user) {
                        throw new MiniException('微信小程序用户授权注册失败');
                    }

                }

                ## 买家信息
                $buyerHandler->register([
                    'open_id' => $openId,
                    'nick_name' => $wxInfo['nickName'],
                    'gender' => $wxInfo['gender'],
                    'avatar_url' => isset($wxInfo['avatarUrl']) ? $wxInfo['avatarUrl'] : '',
                    'language' => isset($wxInfo['language']) ? $wxInfo['language'] : '',
                    'city' => isset($wxInfo['city']) ? $wxInfo['city'] : '',
                    'province' => isset($wxInfo['province']) ? $wxInfo['province'] : '',
                    'country' => isset($wxInfo['country']) ? $wxInfo['country'] : '',
                    'shop_id' => $wxapp->shop_id,
                    'appid' => isset($wxInfo['watermark']['appid']) ? $wxInfo['watermark']['appid'] : ''
                ]);
            });


            if (!$token = auth('api')->login($user)) {

                $result->failed('登录失败,账号和密码不匹配');

                return response()->json($result->toArray(), 401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());

            return response()->json($result->toArray(), 200);
        }


    }

    /**
     * @param LoginRequest $request
     * @param Result $result
     * @param MiniClient $miniClient
     * @param WxappHandler $wxappHandler
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request, Result $result, MiniClient $miniClient, WxappHandler $wxappHandler)
    {
        try {
            $result->succeed($request->all());
            $code = $request->get('code');
            $app_id = $request->get('app_id');
            $wxapp = $wxappHandler->getByAppId($app_id);

            ## 设置 code
            $miniClient->setJsCode($code);
            $miniClient->setAppId($wxapp->app_id);
            $miniClient->setSecret($wxapp->app_secret);
            $wxuser = $miniClient->authCodeAndCode2session();

            $openid = $wxuser['openid'];

            $user = User::where('open_id', $openid)->first();
            if (!$user) {
                return response()->json($result->failed('未授权登录', 100119)->toArray(), 201);
            }

            if (!$token = auth('api')->login($user)) {
                $result->failed('登录异常');
                return response()->json($result->toArray(), 401);
            }

            return $this->respondWithToken($token);

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return $result->toArray();
    }

    /**
     * Get the authenticated User.
     *
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Result $result)
    {
        $user = auth('api')->user();

        $user->load(['buyer']);

        $result->succeed($user);

        return response()->json($result->toArray());
    }

    /**
     * Log the user out (Invalidate the token).
     *
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
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
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
