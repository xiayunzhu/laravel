<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/15
 * Time: 16:52
 */

namespace App\Handlers;


use App\Models\Stock;

class StockHandler
{
    /**
     * 存入数据库
     * @param $item
     * @return bool
     */
    public function store($item)
    {
        if (empty($item)) {
            return false;
        }
        if (!isset($item['sku_code'])) {
            return false;
        }
        $model = Stock::updateOrCreate(['sku_code' => $item['sku_code']], $item);

        return $model;

    }
}