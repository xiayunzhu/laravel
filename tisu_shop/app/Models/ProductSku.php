<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    //
    protected $fillable = ['article_number', 'bar_code', 'brand_id', 'catagory_id', 'color', 'item_code', 'item_name', 'other_prop', 'price', 'spec_code', 'status', 'unit'];

    /**
     * 获取该商品SKU的库存
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function stock()
    {
        return $this->hasOne(Stock::class, 'sku_code', 'spec_code');
    }
}
