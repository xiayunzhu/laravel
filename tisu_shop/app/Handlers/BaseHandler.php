<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-03
 * Time: 20:42
 */

namespace App\Handlers;


class BaseHandler
{
    /**
     * @var array
     */
    protected $scenes = [
        'create' => [],
        'modify' => ['id'],
    ];


    /**
     * 应用场景-过滤数据
     * @param array $arr
     * @param null $scene
     * @return array
     */
    public function currentScene(array $arr, $scene = null)
    {
        //应用场景
        $data = [];
        $sceneLimit = isset($this->scenes[$scene]) ? $this->scenes[$scene] : [];
        foreach ($arr as $k => $v) {
            if (in_array($k, $sceneLimit)) {
                $data[$k] = $v;
            }
        }

        return $data;

    }

    /**
     * @param $data
     * @param array $fmt_arr
     * @return mixed
     */
    public function handleData(array $data, $fmt_arr = ['image_url' => 'image_link'])
    {
        return fmt_array($data, $fmt_arr);
    }
}