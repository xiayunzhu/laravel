<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerAddress extends BaseModel
{
    //
    /**
     * 归属的买家
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use SoftDeletes;

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    /**
     * 归属的店铺
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    const IS_DEFAULT_NO = 0;
    const IS_DEFAULT_YES = 1;
    public static $isDefaultMap = [
        self::IS_DEFAULT_NO => '非默认',
        self::IS_DEFAULT_YES => '默认',
    ];

}
