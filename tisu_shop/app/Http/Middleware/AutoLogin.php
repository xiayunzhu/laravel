<?php

namespace App\Http\Middleware;

use Closure;

class AutoLogin
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
        ## 非生产环境,且是api路由,才能虚拟登录
        if (app()->environment() != 'production' && $request->offsetExists('__debugger')) {
            $action = $request->route()->getAction();
            if (isset($action['middleware']) && is_array($action['middleware'])) {
                if (in_array('api', $action['middleware'])) {
                    $this->apiAutoLogin($request->get('__debugger'));
                    $request->offsetUnset('__debugger');
                }
            }
        }

        return $next($request);
    }

    /**
     * 测试-api请求自动登录
     * @param int $user_id
     */
    private function apiAutoLogin($user_id)
    {
        $user = $user_id ? \App\Models\User::find($user_id) : null;
        if ($user) {
            $token = auth('api')->login($user);

            request()->headers->set('Authorization', 'bearer ' . $token);
        }
    }
}
