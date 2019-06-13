<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/9
 * Time: 17:12
 */

namespace App\Lib\Wln;


class WlnGoodPull extends WLnOpenClient
{
    //修改时间
    private $modify_time;
    //规格编码
    private $spec_code;
    //条码
    private $bar_code;


    public function handle()
    {
        $this->setMethod('erp/goods/spec/open/query');//商品拉取接口
        ## 必要参数
        $this->appendParams(['page' => $this->getPage(), 'limit' => $this->getLimit()]);

        if (!is_null($this->modify_time)) {
            $this->appendParams(['modify_time' => $this->modify_time]);
        }
        if (!is_null($this->spec_code)) {
            $this->appendParams(['spec_code' => $this->spec_code]);
        }
        if (!is_null($this->bar_code)) {
            $this->appendParams(['bar_code' => $this->bar_code]);
        }


        return $this->load();
    }

    /**
     * @return mixed
     */
    public function getModifyTime()
    {
        return $this->modify_time;
    }

    /**
     * @param mixed $modify_time
     */
    public function setModifyTime($modify_time)
    {
        $this->modify_time = $modify_time;
    }

    /**
     * @return mixed
     */
    public function getSpecCode()
    {
        return $this->spec_code;
    }

    /**
     * @param mixed $spec_code
     */
    public function setSpecCode($spec_code)
    {
        $this->spec_code = $spec_code;
    }

    /**
     * @return mixed
     */
    public function getBarCode()
    {
        return $this->bar_code;
    }

    /**
     * @param mixed $bar_code
     */
    public function setBarCode($bar_code)
    {
        $this->bar_code = $bar_code;
    }

}