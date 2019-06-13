<?php

namespace App\Models;

class GoodsGroup extends BaseModel
{
    //
    protected $fillable = ['name', 'shop_id'];

    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['name', 'shop_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(GoodsGroupItem::class, 'goods_group_id', 'id');
    }
}
