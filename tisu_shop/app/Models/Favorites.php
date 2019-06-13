<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Favorites extends BaseModel
{
    //
    use SoftDeletes;

//
    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }
    public  function  shop(){
        return $this->hasOne(Shop::class,'id','shop_id');
    }
    public  function  user(){
        return $this->hasOne(User::class,'id','buyer_id');
    }
}
