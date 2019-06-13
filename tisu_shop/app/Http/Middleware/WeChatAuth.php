<?php

namespace App\Http\Middleware;

use App\Exceptions\BuyerException;
use App\Lib\Response\Result;
use Closure;
use Illuminate\Support\Facades\Auth;

class WeChatAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
                ## 已登录的 微信用户 解析其归属店铺
                $user->load('buyer');
                if ($user) {
                    $user = $user->toArray();
                    $buyer = isset($user['buyer']) ? $user['buyer'] : [];

                    $shop_id = isset($buyer['shop_id']) ? $buyer['shop_id'] : null;
                    $buyer_id = isset($buyer['id']) ? $buyer['id'] : null;
                    if (empty($shop_id)) {
                        throw new BuyerException('异常的买家信息:没有归属商品');
                    }

                    ## 设置买家信息
//                    $_SERVER['buyer'] = $buyer;

                    $request->offsetSet('user_id', isset($user['id']) ? $user['id'] : 0);
                    $request->offsetSet('shop_id', $shop_id);
                    $request->offsetSet('buyer_id', $buyer_id);
                    $request->offsetSet('buyer', $buyer['nick_name']);
                }
            }
            return $next($request);
        } catch (\Exception $exception) {
            if ($exception instanceof BuyerException) {
                $result = new Result();
                $result->failed($exception->getMessage());
                return response()->json($result->toArray());
            }

        }

    }
}
