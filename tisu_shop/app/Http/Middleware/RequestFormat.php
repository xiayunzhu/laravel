<?php

namespace App\Http\Middleware;

use Closure;

class RequestFormat
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
        $format = config('request.default.format');
        if ($format == 'app') {
            $data = $request->get('data');
            if (!empty($data)) {
                $request->offsetUnset('data');//先去除data参数,再解析data的内容, 避免data里面包含 key为data的值
                $data = !is_array($data) ? json_decode($data, true) : $data;
                if (is_array($data)) {
                    foreach ($data as $field => $value) {
                        if ($field == 'pageParams') {
                            if (is_array($value)) {
                                foreach ($value as $k => $v) {
                                    $request->offsetSet($k, $v);
                                }
                            }
                        } else {
                            $request->offsetSet($field, $value);
                        }
                    }
                }
            }
        }


        return $next($request);
    }
}
