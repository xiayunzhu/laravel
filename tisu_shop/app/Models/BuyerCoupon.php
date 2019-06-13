<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 16:36
 */

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerCoupon extends BaseModel
{
    use SoftDeletes;
    protected $table = 'buyer_coupon';
    const STATUS_EFFECT='effect';
    const STATUS_INVALID='invalid';
    const STATUS_USED='used';
    public static $statusMap = [
        self::STATUS_EFFECT => '生效',
        self::STATUS_INVALID => '失效',
        self::STATUS_USED => '已使用',
    ];
    public function promo()
    {
        return $this->hasOne(Promo::class, 'id', 'promo_id');
    }
}