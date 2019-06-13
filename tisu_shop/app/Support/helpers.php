<?php


use Illuminate\Support\Str;

if (!function_exists("is_json")) {
    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('getModelFields')) {
    /**
     * 返回模型的所有字段
     *
     * @param string $model
     * @param string $prefix
     * @return array|string
     */
    function getModelFields(string $model, $prefix = 'App\\Models\\')
    {
        try {
            $modelNew = app($prefix . $model);
            $columns = $modelNew->getFillable();
            if (!count($columns)) {
                $table = $modelNew->getTable();
                $columns = Illuminate\Support\Facades\Schema::getColumnListing($table);
            }

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        return $columns;
    }
}

if (!function_exists("create_object_id")) {
    /**
     * 生成 object_id
     */
    function create_object_id()
    {
        return base_convert(uniqid(), 16, 10);
    }
}

if (!function_exists('array_diff_assoc_serialize')) {
    /**
     * 比较数组，返回差异
     *
     * 支持value数据类型为数组
     * @param $currData
     * @param $orgData
     * @return array
     */
    function array_diff_assoc_serialize($currData, $orgData)
    {
        $currData = array_map('serialize', $currData);
        $orgData = array_map('serialize', $orgData);

        $arr_diff = array_diff_assoc($currData, $orgData);

        return array_map('unserialize', $arr_diff);

    }
}

if (!function_exists('storage_url')) {
    /**
     * 获取完整的 URL
     */
    function storage_url($path)
    {
        return \Illuminate\Support\Facades\Storage::url($path);
    }
}
if (!function_exists('storage_image_url')) {
    /**
     * 获取图片完整 URL
     * @param $path
     * @return string
     */
    function storage_image_url($path)
    {
        if (!Str::startsWith($path, 'http')) {
            $path = !empty($path) ? storage_url($path) : config('app.url') . '/images/pic-none.png';
        }

        return $path;
    }
}


if (!function_exists('fmt_val')) {
    /*
 *
 * 时间或者价格数值的处理
 * @ array_walk_recursive($qualityItem, 'fmtval', $fmt_arr); 调用方法事例
 * @ $qualityItem :要循环的数组
 * @ fmtval： 方法名称
 * @ $fmt_arr: 需要处理的字段数组
 * @ $fmt_arr的array形式规则：$fmt_arr = array('time'=>'sec','pri'=>'fee','created' => "{data}?date('Y-m-d',{data}):''",'status'=>['WAIT'=>'待付款', 'PAY' => '待客审'])
 */

    function fmt_val(&$val, $key, $exfld)
    {
        if (isset($exfld[$key])) {
            if (is_array($exfld[$key])) { ##支持数组key=>val 映射val值
                $switch_map = $exfld[$key];
                $val = isset($switch_map[$val]) ? $switch_map[$val] : $val;
            } else {
                switch ($exfld[$key]) {
                    case 'fee':
                        $val /= 10000;
                        break;
                    case 'pri':
                        $val /= 100;
                        $val = number_format($val, 2);
                        break;
                    case 'sec':
                        if ($val > 0) {
                            $val = date('Y-m-d H:i:s', $val);
                        }
                        break;
                    case 'ymd':
                        if ($val > 0) {
                            $val = date('Y-m-d', $val);
                        }
                        break;
                    case 'image_link':## 图片链接处理方式
                        $val = storage_image_url($val);
                        break;
                    default: #eval
                        if (!is_null($val) && $val !== '') {
                            $eval = '$val =' . strtr($exfld[$key], array('{data}' => $val)) . ';';
                            eval($eval);
                        }
                        break;
                }
            }
        }
    }
}


if (!function_exists('fmt_array')) {
    /**
     * 数组格式转换（代号转成可读的内容）
     * @param array $data
     * @param array $fmt_arr
     * @return array
     */
    function fmt_array($data = array(), $fmt_arr = array())
    {
        if (function_exists('fmt_val')) {
            array_walk_recursive($data, 'fmt_val', $fmt_arr);
        }

        return $data;
    }
}

if (!function_exists('calBorrowLevel')) {

    function calBorrowLevel($days)
    {
        $level = 0;
        if (empty($days)) {
            return 0;
        }
        $arr_level_values = [1, 3, 5, 7, 15, 20, 30];

        foreach ($arr_level_values as $key => $value) {
            if ($key == 0) {
                if ($days <= $value) {
                    $level = $value;
                    break;
                }
            } else {
                if ($days <= $value && $days > prev($arr_level_values)) {
                    $level = $value;
                    break;
                }
            }
        }

        if ($level == 0) {
            if ($days > end($arr_level_values)) {
                $level = end($arr_level_values);
            }
        }

        return $level;

    }
}

if (!function_exists('lang')) {
    /**
     * Trans for getting the language.
     *
     * @param string $text
     * @param  array $parameters
     * @return string
     */
    function lang($text, $parameters = [])
    {
        return trans('blog.' . $text, $parameters);
    }
}

if (!function_exists("is_mobile")) {
    /**
     * 判断是否为手机
     *
     * @return bool
     */
    function is_mobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'mobile',
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

if (!function_exists('is_email')) {
    /**
     * 判断是否为正常邮箱格式
     *
     * @param $mail
     * @return bool
     */
    function is_email($mail)
    {
        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
        preg_match($pattern, $mail, $matches);
        return isset($matches[0]) ? $matches[0] == $mail : false;
    }
}

if (!function_exists('is_phone_number')) {
    /**
     *  判断是否手机号
     *
     * @param  $phone_number
     * @return bool
     */
    function is_phone_number($phone_number)
    {
        if (preg_match("/^1[345678]{1}\d{9}$/", $phone_number)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('arraySort')) {
    /**
     *  二维数组根据指定键排序
     *
     * @param  $array
     * @param  $keys
     * @param  $sort
     * @return array
     */
    function arraySort($array, $keys, $sort = 'asc')
    {
        $newArr = $valArr = array();
        foreach ($array as $key => $value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ? asort($valArr) : arsort($valArr);
        reset($valArr);
        foreach ($valArr as $key => $value) {
            $newArr[$key] = $array[$key];
        }
        return array_values($newArr);
    }
}

if (!function_exists('sizeSort')) {
    /**
     * 衣服尺码排序
     *
     * @param $a
     * @param $b
     * @return int
     */
    function sizeSort($a, $b)
    {
        $map = [
            'XXXXS' => 0,
            'XXXS' => 1,
            'XXS' => 2,
            'XS' => 3,
            'S' => 4,
            'M' => 5,
            'L' => 6,
            'XL' => 7,
            'XXL' => 8,
            'XXXL' => 9,
            'XXXXL' => 10,
            'XXXXXL' => 11,
        ];
        return ($map[strtoupper($a)] > $map[strtoupper($b)]) ? 1 : -1;
    }
}
if (!function_exists('statusSort')) {
    /**
     * 衣服尺码排序
     *
     * @param $a
     * @param $b
     * @return int
     */
    function statusSort($a, $b)
    {
        $map = [
            'effect' => 0,
            'invalid' => 1,
            'used' => 2,
        ];
        return ($map[strtoupper($a)] > $map[strtoupper($b)]) ? 1 : -1;
    }
}


if (!function_exists('byte_to_size')) {
    /**
     *
     * @param $byte
     * @return string
     */
    function byte_to_size($byte)
    {
        if ($byte > pow(2, 40)) {
            $size = round($byte / pow(2, 40), 2) . ' TB';
        } elseif ($byte > pow(2, 30)) {
            $size = round($byte / pow(2, 30), 2) . ' GB';
        } elseif ($byte > pow(2, 20)) {
            $size = round($byte / pow(2, 20), 2) . ' MB';
        } elseif ($byte > pow(2, 10)) {
            $size = round($byte / pow(2, 10), 2) . ' KB';
        } else {
            $size = round($byte, 2) . ' B';
        }

        return $size;
    }
}
