<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsHasSpec extends BaseModel
{
    use SoftDeletes;

    public static $fields = ['goods_id','spec_id','spec_value_id','shop_id'];

    /**
     * 商品参数规格查询
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function spec(){
        return $this->hasOne(Specs::class, 'id', 'spec_id')
            ->select(['id', 'spec_name']);
    }
    /**
     * 商品参数规格属性值查询
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function specValue(){
        return $this->hasOne(SpecValues::class, 'id', 'spec_value_id')
            ->select(['id', 'spec_value']);
    }

}
