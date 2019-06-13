<?php

namespace App\Models;
use App\Exceptions\InternalException;

class CartItem extends BaseModel
{

    protected $fillable = ['user_id', 'goods_spec_id', 'num'];
    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['user_id', 'goods_spec_id', 'num'];

    /**
     * 购物车内商品信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function good_spec()
    {
        return $this->hasOne(GoodsSpec::class, 'id', 'goods_spec_id')
            ->select(['id', 'goods_id', 'goods_price', 'spec_name', 'image_url', 'publish_status', 'sales_status']);
    }

    /**
     * 购物车商品数量添加
     * @param $amount
     * @throws InternalException
     */
    public function addNum($amount)
    {
        if ($amount < 0) {
            throw new InternalException('数量不可小于0');
        }
        $this->increment('num', $amount);
    }
}
