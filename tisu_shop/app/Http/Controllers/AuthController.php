<?php

namespace App\Http\Controllers;

use App\Lib\Response\Result;
use Illuminate\Http\Request;

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
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $username = $this->username();

        $credentials = request([$username, 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            $result = new Result();
            $result->failed('登录失败,账号和密码不匹配');
            
            return response()->json($result->toArray(), 401);
        }

        return $this->respondWithToken($token);
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
