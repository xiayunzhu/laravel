<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends BaseModel

{
    use SoftDeletes;

    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['brand_id', 'category_id', 'content', 'deduct_stock_type', 'delivery_id', 'goods_sort', 'introduction', 'name', 'publish_status', 'sales_actual', 'sales_initial', 'sales_status', 'shop_id', 'spec_type', 'title', 'version', 'org_goods_id', 'confirm_status'];

    //
    const SPEC_TYPE_ONE = 0;
    const SPEC_TYPE_MORE = 1;

    public static $specTypeMap = [
        self::SPEC_TYPE_ONE => "单规格",
        self::SPEC_TYPE_MORE => "多规格",
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


    //   修改确认
    const  CONFIRM_STATUS_WAIT = 0;
    const  CONFIRM_STATUS_DONE = 1;
    public static $confirmStatusMap = [
        self::CONFIRM_STATUS_WAIT => '待确认',
        self::CONFIRM_STATUS_DONE => '已确认',
    ];

    /**
     * 关联的所有图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(GoodsImage::class, 'goods_id', 'id');
    }

    /**
     * 商品关联的主题图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function main_images()
    {
        return $this->hasMany(GoodsImage::class, 'goods_id', 'id')
            ->where('property', GoodsImage::PROPERTY_MAIN)
            ->orderBy('sort', 'asc')
            ->select(['id', 'goods_id', 'file_url', 'sort']);
    }

    /**
     * 列表LOGO 图
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function logo_image()
    {
        return $this->hasOne(GoodsImage::class, 'goods_id', 'id')
            ->where('property', GoodsImage::PROPERTY_MAIN)
            ->orderBy('sort', 'asc')
            ->select(['id', 'goods_id', 'file_url']);
    }

    /**
     * 商品详情图
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_images()
    {
        return $this->hasMany(GoodsImage::class, 'goods_id', 'id')
            ->where('property', GoodsImage::PROPERTY_DETAIL)
            ->orderBy('sort', 'asc')
            ->select(['id', 'goods_id', 'file_url', 'sort', 'is_show']);
    }

    /**
     * 商品SKU
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specs()
    {
        return $this->hasMany(GoodsSpec::class, 'goods_id', 'id');
    }

    /**
     * 归属的原商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function org_goods()
    {
        return $this->belongsTo(OrgGood::class, 'org_goods_id', 'id');
    }

    /**
     * 商品参数规格
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function has_specs()
    {
        return $this->hasMany(GoodsHasSpec::class, 'goods_id', 'id')->select(['id', 'goods_id', 'spec_id', 'spec_value_id']);
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
     * 商品详情SKU
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goodSpecs()
    {
        return $this->hasMany(GoodsSpec::class, 'goods_id', 'id')->select(['id', 'goods_id', 'publish_status', 'sold_num', 'quantity','org_goods_specs_id']);
    }

    /**
     * 注册 监听事件
     */
    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::deleting(function (Goods $model) {
            $model->specs()->delete();
            $model->has_specs()->delete();
            $model->images()->delete();
        });
    }
}
