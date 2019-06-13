<?php

namespace App\Models;

class GoodsGroupItem extends BaseModel
{
    //
//    protected $fillable = ['goods_group_id', 'goods_id', 'shop_id'];
    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['goods_group_id', 'goods_id', 'shop_id'];


    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id')
            ->select(['id','name','quantity','sales_actual','publish_status']);
    }
}
