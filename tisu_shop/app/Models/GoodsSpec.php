<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class GoodsSpec extends BaseModel
{
    use SoftDeletes;

    public static $fields = ['goods_id', 'goods_no', 'goods_price', 'line_price', 'quantity', 'quantity_offset', 'sold_num', 'virtual_sold_num', 'barcode', 'weight', 'shop_id', 'publish_status', 'sales_status', 'spec_code', 'org_goods_specs_id', 'spec_name', 'image_url', 'shelves_num', 'size', 'color', 'retail_price', 'virtual_quantity'];

    public function org_goods_spec()
    {
        return $this->belongsTo(OrgGoodsSpec::class, 'org_goods_specs_id', 'id');
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    /**
     * 商品
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function good()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id')
            ->select(['id', 'name', 'sales_status', 'publish_status']);
    }

    /**
     * 计算商品库存
     * @return int
     */
    public function getQuantityAttribute()
    {
        ## cacheKey
        $quantity = $this->getQty();

        if (empty($quantity)) {
            $quantity = OrgGoodsSpec::where('id',$this->org_goods_specs_id)->value('virtual_quantity');
//            if ($org_goods_spec) {
//                $quantity = $org_goods_spec->virtual_quantity;
//            }

            $this->setQty($quantity);
        }

        return $quantity;
    }

    /**
     * 获取库存
     * @return mixed
     */
    public function getQty()
    {
        return Cache::get($this->cacheKeyQty());
    }

    /**
     * 设置库存
     * @param $quantity
     * @param int $minutes
     * @return mixed
     */
    public function setQty($quantity, $minutes = 1)
    {
        return Cache::put($this->cacheKeyQty(), $quantity, $minutes);
    }

    /**
     * 增加库存
     * @param int $num
     * @return mixed
     */
    public function incrQty($num = 1)
    {

        return Cache::increment($this->cacheKeyQty(), $num);
    }

    /**
     * 减少库存
     * @param int $num
     * @return mixed
     */
    public function decrQty($num = 1)
    {
        return Cache::decrement($this->cacheKeyQty(), $num);

    }

    /**
     * 库存Key
     * @return string
     */
    public function cacheKeyQty()
    {
        return 'qty_' . $this->id;
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

    //publish_status string(16)
    const PUBLISH_STATUS_UPPER = 'upper';
    const PUBLISH_STATUS_LOWER = 'lower';
    const PUBLISH_STATUS_PLATFORM_LOWER = 'platform_lower';
    const PUBLISH_STATUS_INFO_CHANGE = 'info_change';
    public static $publishStatusMap = [
        self::PUBLISH_STATUS_UPPER => '上架',
        self::PUBLISH_STATUS_LOWER => '下降',
        self::PUBLISH_STATUS_PLATFORM_LOWER => '平台下架',
        self::PUBLISH_STATUS_INFO_CHANGE => '信息变更',
    ];
    /**
     * 规格参数
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goodSpecs()
    {
        return $this->hasMany(GoodsSpecname::class, 'org_goods_specs_id', 'org_goods_specs_id')
            ->where('status',GoodsSpecname::STATUS_YES)->select(['org_goods_specs_id','spec_name','spec_value']);
    }

}
