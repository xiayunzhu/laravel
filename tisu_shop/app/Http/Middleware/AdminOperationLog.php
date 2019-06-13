<?php

namespace App\Http\Middleware;

use App\Models\OperationLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminOperationLog
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
        $user_id = 0;
        if (Auth::check()) {
            $user_id = (int)Auth::id();
        }
        $_SERVER['admin_uid'] = $user_id;
        if ('GET' != $request->method()) {
            $input = $request->all();
            $log = new OperationLog(); # 提前创建表、model
            $log->uid = $user_id;
            $log->path = $request->path();
            $log->method = $request->method();
            $log->ip = $request->ip();
            $log->sql = '';

            //敏感字段加密
            $guardFields = ['password'];
            foreach ($guardFields as $field) {
                if (isset($input[$field])) {
                    $input[$field] = Crypt::encryptString($input[$field]);
                }
            }


            $log->input = json_encode($input, JSON_UNESCAPED_UNICODE);

            $log->save();   # 记录日志
        }

        return $next($request);
    }
}
