<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\InternalException;

class OrgGoodsSpec extends BaseModel
{
    use SoftDeletes;

    public static $fields = ['id', 'org_goods_id', 'org_goods_no', 'org_goods_price', 'line_price','quantity', 'virtual_quantity', 'quantity_offset', 'virtual_sold_num', 'sold_num', 'barcode', 'weight', 'spec_name', 'publish_status', 'spec_code', 'org_goods_specs_id', 'image_url','commission_rate'];


    const PRICE_CHANGE_YES = 'YES';
    const PRICE_CHANGE_NO = 'NO';

    ## 改价权限
    public static $priceChangeMap = [
        self::PRICE_CHANGE_YES => "允许",
        self::PRICE_CHANGE_NO => "不允许",
    ];

    /**
     * 库存扣减
     * @param $amount
     * @return int
     * @throws InternalException
     */
    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('减库存不可小于0');
        }

        return $this->newQuery()->where('id', $this->id)->where('virtual_quantity', '>=', $amount)->decrement('virtual_quantity', $amount);
    }

    /**
     * 库存回复
     * @param $amount
     * @throws InternalException
     */
    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('virtual_quantity', $amount);
    }

    /**
     * 添加销售数量
     * @param $num
     * @throws InternalException
     */
    public function addSaleNum($num)
    {
        if ($num < 0) {
            throw new InternalException('加销量不可小于0');
        }
        $this->increment('sold_num', $num);
        $this->increment('virtual_sold_num', $num);
    }
    /**
     * 规格参数
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goodSpecs()
    {
        return $this->hasMany(GoodsSpecname::class, 'org_goods_specs_id', 'id')
            ->where('status',GoodsSpecname::STATUS_YES)->select(['org_goods_specs_id','spec_name','spec_value']);
    }
}
