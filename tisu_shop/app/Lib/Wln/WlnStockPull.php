<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/14
 * Time: 18:39
 */

namespace App\Lib\Wln;


class WlnStockPull extends WlnB2cClient
{

    /**
     * Datetime 是 修改库存的开始时间，格式：yyyy-MM-dd HH:mm:ss
     * @var string
     */
    private $start;

    /**
     * Datetime 是 修改库存的结束时间，格式：yyyy-MM-dd HH:mm:ss
     * @var string
     */
    private $end;

    /**
     *  String 否 ERP 中的仓库编码，默认返回所有仓库的库存
     * @var string
     */
    private $storage_code;


    public function handle()
    {
        $this->setMethod('v1/inventories/erp');//批量查询库存

        $this->appendParams(['start' => $this->start, 'end' => $this->end, 'page' => $this->getPage(), 'limit' => $this->getLimit()]);
        // limit 不传 万里牛会报错 500,服务器内部错误

        if (!empty($this->storage_code)) {
            $this->appendParams(['storage_code' => $this->storage_code]);
        }
        //设置请求方式为 GET
        $this->setRequestMethod('GET');

        return $this->load();
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start)
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd(string $end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getStorageCode(): string
    {
        return $this->storage_code;
    }

    /**
     * @param string $storage_code
     */
    public function setStorageCode(string $storage_code)
    {
        $this->storage_code = $storage_code;
    }
}