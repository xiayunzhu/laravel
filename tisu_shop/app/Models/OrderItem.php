<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $fillable = [
        'order_id', 'order_no', 'item_no', 'goods_id',
        'goods_name', 'image_url', 'goods_spec_id', 'org_goods_specs_id',
        'goods_no', 'goods_price', 'line_price', 'num',
        'receivable', 'payment', 'user_id', 'shop_id',
        'create_time', 'status', 'has_refund','spec_name','spec_code'
    ];
    /**
     * 字段
     * @var array
     */
    public static $fields = ['order_no', 'item_no', 'goods_id', 'goods_name', 'image_url', 'deduct_stock_type', 'spec_code', 'goods_spec_id', 'org_goods_specs_id', 'goods_no', 'goods_price', 'line_price', 'weight', 'num', 'receivable', 'payment', 'user_id', 'shop_id', 'create_time', 'status', 'has_refund','spec_name'];

    /**
     * @param $orderNo
     * @param $index
     * @return string
     */
    public static function createItemNo($orderNo, $index)
    {
        return $orderNo . '-' . ($index + 1);
    }

    //扣减库存的方式
    const DEDUCT_STOCK_TYPE_CREATE = 1;
    const DEDUCT_STOCK_TYPE_PAYED = 2;
    public static $deductStockTypeMap = [
        self::DEDUCT_STOCK_TYPE_CREATE => '下单扣减库存',
        self::DEDUCT_STOCK_TYPE_PAYED => '付款扣减库存',
    ];

    // 规格类型-spec_type  单规格 多规格
    const SPEC_TYPE_SINGLE = 1;
    const SPEC_TYPE_MORE = 2;
    public static $specTypeMap = [
        self::SPEC_TYPE_SINGLE => '单规格',
        self::SPEC_TYPE_MORE => '多规格',
    ];
    //明显状态 1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；
    const STATUS_WAIT = 1;
    const STATUS_PAY = 2;
    const STATUS_DONE = 3;
    const STATUS_CLOSE = 4;
    const STATUS_CONFIRM = 5;
    public static $statusMap = [
        self::STATUS_WAIT => '等待付款',
        self::STATUS_PAY => '等待发货',
        self::STATUS_CONFIRM => '等待确认',
        self::STATUS_DONE => '已完成',
        self::STATUS_CLOSE => '已关闭',

    ];

    //是否为退款 has_refund  0：无退款；1：申请退款；2.同意退款；3：拒绝退款；4：关闭退款；5：完成退款；
    const HAS_REFUND_UN_REFUND = 0;
    const HAS_REFUND_REFUND = 1;
    const HAS_REFUND_REFUNDING = 2;
    const HAS_REFUND_REFUSE = 3;
    const HAS_REFUND_CLOSE = 4;
    const HAS_REFUND_FINISH = 5;
    public static $hasRefundMap = [
        self::HAS_REFUND_UN_REFUND => '无退款',
        self::HAS_REFUND_REFUND => '申请退款',
        self::HAS_REFUND_REFUNDING => '同意退款',
        self::HAS_REFUND_REFUSE => '拒绝退款',
        self::HAS_REFUND_CLOSE => '关闭退款',
        self::HAS_REFUND_FINISH => '完成退款',
    ];



    /**
     * 归属的商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    /**
     * 归属的商品SKU
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goodsSpec()
    {
        return $this->belongsTo(GoodsSpec::class, 'goods_spec_id', 'id');
    }

    /**
     * 归属的原商品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orgGoodsSpec()
    {
        return $this->belongsTo(OrgGoodsSpec::class, 'org_goods_specs_id', 'id');
    }

    /**
     * 归属的订单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_no', 'order_no');
    }

}
