<?php

namespace App\Http\Middleware;

use Closure;

class LoginAccount
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
        $account = $request->get('account') ?: null;
        if (!empty($account)) {

            //正则判断
            if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {

                //手机号码登录
                if (is_phone_number($account)) {
                    $__account_field = 'phone';
                } else {
                    //用户账号登录
                    $__account_field = 'username';
                }

            } else {
                //是邮箱登录
                $__account_field = 'email';
            }
            if (!empty($__account_field)) {
                $request->offsetSet($__account_field, $account);//添加参数
                $request->offsetSet('login_field', $__account_field);//添加参数
            }

//            $request->offsetSet('account', '');
        }


        return $next($request);
    }
}
