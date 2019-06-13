<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:51
 */

namespace App\Models;


class EventItem extends BaseModel
{

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'id');
    }
}