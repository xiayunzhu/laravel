<?php

namespace App\Models;

class OrgGoodsHasSpec extends BaseModel
{

    public static $fields = ['org_goods_id','spec_id','spec_value_id'];

    /**
     * 获取该商品已有参数属性
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function goodsSpec(){
        return $this->hasOne(Specs::class,'id','spec_id');
    }
    /**
     * 归属于某个商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function good(){
        return $this->belongsTo(OrgGood::class,'org_goods_id','id');
    }

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
