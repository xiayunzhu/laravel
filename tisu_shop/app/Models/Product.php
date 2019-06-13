<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $fillable = ['article_number', 'bar_code', 'brand_id', 'catagory_id', 'color', 'item_code', 'item_name', 'other_prop', 'price', 'spec_code', 'status', 'unit'];

    public function skus()
    {
        return $this->hasMany(ProductSku::class, 'item_code', 'item_code');
    }

    /**
     * 获取该商品规格库存
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function stock()
    {
        return $this->hasOne(Stock::class, 'sku_code', 'spec_code');
    }
}
