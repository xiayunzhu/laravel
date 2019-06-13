<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 16:20
 */

namespace App\Handlers;


use App\Models\OrderItem;

class OrderItemHandler
{

    /**
     * @param $data
     * @return mixed
     */
    public function store($data, $index)
    {
        $model = new OrderItem();
        foreach (OrderItem::$fields as $field) {
            if (isset($data[$field])) {
                $model->$field = $data[$field];
            }
        }

        $model->item_no = OrderItem::createItemNo($model->order_no, $index);
        $model->deduct_stock_type = OrderItem::DEDUCT_STOCK_TYPE_CREATE;
        $model->status = OrderItem::STATUS_WAIT;
        $model->has_refund = OrderItem::HAS_REFUND_UN_REFUND;
        $model->create_time = time();

        $model->save();

        return $model;
    }
}