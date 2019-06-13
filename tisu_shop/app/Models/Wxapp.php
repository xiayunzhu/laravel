<?php

namespace App\Models;


class Wxapp extends BaseModel
{

    /**
     * 获取该小程序归属店家：店铺
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop(){
        return $this->hasOne(Shop::class,'id','shop_id');
    }
}
