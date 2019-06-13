<?php

namespace App\Models;


class PageContentsGood extends BaseModel
{
    public static $fields = ['page_contents_id', 'goods_id'];
    /**
     * 关联商品
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function good()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id')
            ->select(['id','name', 'goods_price', 'quantity', 'sales_actual']);
    }
}
