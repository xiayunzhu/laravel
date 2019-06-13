<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/15
 * Time: 12:06
 */

namespace App\Models;

class Teasing extends BaseModel
{
    public function teasingImg(){
        return $this->hasMany(TeasingImg::class,'teasing_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}