<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 15:58
 */

namespace App\Handlers;


use App\Exceptions\RefundsException;
use App\Models\RefundLogistics;

class RefundLogisticsHandler
{
    /**
     * @param $data
     * @return mixed
     * @throws  RefundsException
     */
    public function store($data)
    {
        $model = new RefundLogistics();
        foreach (RefundLogistics::$fields as $field) {
            if (empty($data[$field]))
                throw new RefundsException($field.'不能为空');
            if (isset($data[$field])) {
                $model->$field = $data[$field];
            }
        }


        $model->save();

        return $model;
    }
}