<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 15:58
 */

namespace App\Handlers;


use App\Models\OrderAddress;

class OrderAddressHandler
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $model = new OrderAddress();
        foreach (OrderAddress::$fields as $field) {
            if (isset($data[$field])) {
                $model->$field = $data[$field];
            }
        }

        $model->create_time=time();

        $model->save();

        return $model;
    }
}