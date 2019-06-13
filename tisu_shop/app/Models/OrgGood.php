<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


class OrgGood extends BaseModel
{
    use SoftDeletes;
    public static  $fields = ['id', 'name', 'title', 'brand_id', 'category_id', 'spec_type', 'deduct_stock_type', 'content', 'introduction', 'sales_initial', 'sales_actual', 'goods_sort', 'delivery_id', 'sales_status', 'publish_status', 'version', 'specs', 'images', 'specs_values','label_values','commission_rate'];

    const SPEC_TYPE_ONE = 0;
    const SPEC_TYPE_MORE = 1;

    public static $specTypeMap = [
        self::SPEC_TYPE_ONE => "单规格",
        self::SPEC_TYPE_MORE => "多规格",
    ];


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


    // sales_status string(16) - SOLD_OUT:售罄,ON_SALE:在售, PRE_SALE:预售
    const SALE_STATUS_SOLD_OUT = 'sold_out';
    const SALE_STATUS_ON_SALE = 'on_sale';
    const SALE_STATUS_PRE_SALE = 'pre_sale';
    public static $saleStatusMap = [
        self::SALE_STATUS_SOLD_OUT => '售罄',
        self::SALE_STATUS_ON_SALE => '在售',
        self::SALE_STATUS_PRE_SALE => '预售',
    ];


    /**
     * 获取该商品规格
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specs()
    {
        return $this->hasMany(OrgGoodsSpec::class, 'org_goods_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function spec()
    {
        return $this->hasOne(OrgGoodsSpec::class, 'org_goods_id', 'id');
    }

    /**
     * 获取该商品图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(OrgGoodImage::class, 'org_goods_id', 'id');
    }

    /**
     * 商品关联的主题图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function main_images()
    {
        return $this->hasMany(OrgGoodImage::class, 'org_goods_id', 'id')
            ->where('property', OrgGoodImage::PROPERTY_MAIN)
            ->orderBy('sort', 'asc')
            ->select(['id', 'org_goods_id', 'file_url', 'sort']);
    }

    /**
     * 列表LOGO 图
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function logo_image()
    {
        return $this->hasOne(OrgGoodImage::class, 'org_goods_id', 'id')
            ->where('property', OrgGoodImage::PROPERTY_MAIN)
            ->orderBy('sort', 'asc')
            ->select(['id', 'org_goods_id', 'file_url']);
    }


    /**
     * 商品详情图
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_images()
    {
        return $this->hasMany(OrgGoodImage::class, 'org_goods_id', 'id')
            ->where('property', OrgGoodImage::PROPERTY_DETAIL)
            ->orderBy('sort', 'asc')
            ->select(['id', 'org_goods_id', 'file_url', 'sort']);
    }

    /**
     * 商品详情规格
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_specs()
    {
        return $this->hasMany(OrgGoodsHasSpec::class, 'org_goods_id', 'id');
    }

    /**
     * 归属的品类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * 归属的品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * 归属的模板
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveries()
    {
        return $this->belongsTo(Deliveries::class, 'delivery_id', 'id');
    }

    /**
     * 获取该商品参数规格
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function has_specs()
    {
        return $this->hasMany(OrgGoodsHasSpec::class, 'org_goods_id', 'id');
    }

    /**
     * 获取Goods
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goods()
    {
        return $this->hasMany(Goods::class, 'org_goods_id', 'id');
    }

    /**
     * 获取该商品标签
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lables()
    {
        return $this->hasMany(OrgGoodsLabels::class, 'org_goods_id', 'id')
            ->select(['id', 'org_goods_id', 'label_value']);
    }

    /**
     * 商品运费模板规则
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function delivery_rules()
    {
        return $this->hasMany(DeliveryRule::class, 'delivery_id', 'delivery_id')
            ->orderBy('id', 'asc')->select();
    }

    /**
     * 商品参数
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function params()
    {
        return $this->hasMany(GoodsParameter::class, 'org_goods_id', 'id')
            ->where('status',GoodsParameter::STATUS_ON_SHOW)->select(['org_goods_id','parameter_name','parameter_value']);
    }

}
