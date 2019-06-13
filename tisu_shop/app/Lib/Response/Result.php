<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/13
 * Time: 18:26
 */

namespace App\Lib\Response;


class Result extends \Ml\Response\Result
{

    public function __construct()
    {
        $this->setMessage('成功');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        //设置返回值 格式
        $default = config('result.default');
        switch ($default) {
            case 'app':
                //APP端
                return [
                    'status' => intval($this->isSuccess()),
                    'result' => $this->getMessage(),
                    'data' => $this->getModel()
                ];
                break;
            default:

                return [
                    'code' => $this->getCode(),
                    'message' => $this->getMessage(),
                    'success' => $this->isSuccess(),
                    'model' => $this->getModel(),
                ];
                break;
        }

    }
}