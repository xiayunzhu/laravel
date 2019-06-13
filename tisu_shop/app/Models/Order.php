<?php

namespace App\Models;



class Order extends BaseModel
{
    protected $fillable = ['order_no', 'shop_name', 'shop_nick', 'source', 'total_fee', 'paid_fee', 'discount_fee', 'post_fee', 'service_fee', 'pay_status', 'pay_time', 'express_price', 'express_company', 'express_no', 'send_status', 'send_time', 'receipt_status', 'receipt_time', 'refund_status', 'order_status', 'order_type', 'close_type', 'close_time', 'create_time', 'update_time', 'buyer_msg', 'seller_msg', 'buyer', 'shop_id','user_id'];

    /***
     * 归属用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 收件地址
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(OrderAddress::class, 'order_no', 'order_no')
            ->select('id','order_no','receiver','mobile','province','city','district','detail','zip_code');
    }

    /**
     * 商品明细
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_no', 'order_no')
            ->select('goods_spec_id','item_no','has_refund','order_no','goods_name','goods_price','spec_name','num','image_url');
    }

    /**
     * 字段
     *
     * @var array
     */
    public static $fields = ['order_no', 'shop_name', 'shop_nick', 'source', 'total_fee', 'paid_fee', 'discount_fee', 'service_fee', 'pay_status', 'pay_time', 'express_company', 'express_no', 'send_status', 'send_time', 'receipt_status', 'receipt_time', 'refund_status', 'order_status', 'order_type', 'close_type', 'close_time', 'create_time', 'update_time', 'buyer_msg', 'seller_msg', 'buyer', 'user_id', 'shop_id'];


//      public $timestamps= false;

    //订单来源
    const SOURCE_WE_CHAT_APPLET = 'WeChat_applet';

    public static $sourceMap = [
        self::SOURCE_WE_CHAT_APPLET => '微信小程序商城',
    ];

    // 付款状态
    const PAY_STATUS_WAIT = 0;
    const PAY_STATUS_DONE = 1;

    public static $payStatusMap = [
        self::PAY_STATUS_WAIT => '未付款',
        self::PAY_STATUS_DONE => '已付款'
    ];

    // 发货状态
    const SEND_STATUS_WAIT = 0;
    const SEND_STATUS_DONE = 1;

    public static $sendStatusMap = [
        self::SEND_STATUS_WAIT => '待发货',
        self::SEND_STATUS_DONE => '已发货',
    ];

    // 收货状态 receipt_status
    const RECEIPT_STATUS_WAIT = 0;
    const RECEIPT_STATUS_DONE = 1;

    public static $receiptStatusMap = [
        self::RECEIPT_STATUS_WAIT => '待收货',
        self::RECEIPT_STATUS_DONE => '已收货',
    ];

    //退款状态 refund_status
    const REFUND_STATUS_WAIT = 0;
    const REFUND_STATUS_DONE = 1;

    public static $refundStatusMap = [
        self::REFUND_STATUS_WAIT => '未申请退款',
        self::REFUND_STATUS_DONE => '已申请退款',
    ];

    //订单状态 order_status WAIT：等待付款，PAYED：已付款,待发货，SEND_FEW：部分发货，SEND_ALL：已发货(全)，WAIT_CONFIRM：待确认收货,DONE：确认收货，CLOSE：关闭订单 0：未创建交易；1：等待付款；2：等待发货；3：已完成；4：已关闭；5：等待确认；6：已签收
    const ORDER_STATUS_WAIT = 'WAIT';
    const ORDER_STATUS_PAYED = 'PAYED';
    const ORDER_STATUS_SEND = 'SEND';
    const ORDER_STATUS_SEND_FEW = 'SEND_FEW';
    const ORDER_STATUS_SEND_ALL = 'SEND_ALL';
    const ORDER_STATUS_WAIT_CONFIRM = 'WAIT_CONFIRM';
    const ORDER_STATUS_DONE = 'DONE';
    const ORDER_STATUS_SIGN = 'SIGN';
    const ORDER_STATUS_CLOSE = 'CLOSE';

    public static $orderStatusMap = [
        self::ORDER_STATUS_WAIT => '等待付款',
        self::ORDER_STATUS_PAYED => '已付款,待发货',
        self::ORDER_STATUS_SEND => '已发货',
        self::ORDER_STATUS_SEND_FEW => '部分发货',
        self::ORDER_STATUS_SEND_ALL => '已发货(全)',
        self::ORDER_STATUS_WAIT_CONFIRM => '待确认收货',
        self::ORDER_STATUS_DONE => '确认收货',
        self::ORDER_STATUS_SIGN => '已签收',
        self::ORDER_STATUS_CLOSE => '关闭订单',
    ];

    public static $orderStatusAPPMap = [
        self::ORDER_STATUS_WAIT => '待付款',
        self::ORDER_STATUS_PAYED => '待发货',
        self::ORDER_STATUS_SEND => '已发货',
        self::ORDER_STATUS_SIGN => '已完成',
        self::ORDER_STATUS_CLOSE => '交易关闭',
    ];

    //订单类型 order_type - 0: 普通订单
    const ORDER_TYPE_REGULAR = 0;

    public static $orderTypeMap = [
        self::ORDER_TYPE_REGULAR => '普通订单'
    ];

    //订单关闭 close_type - 0: 未关闭,1: 过期关闭,2: 标记退款,3: 超时订单取消,4: 买家取消,5: 卖家取消,6: 部分退款,10: 无法联系上卖家,11: 卖家误拍或重拍,12: 买家无诚意完成交易,13: 商品缺货无法交易
    const CLOSE_TYPE_NO = 0;
    const CLOSE_TYPE_EXPIRE = 1;
    const CLOSE_TYPE_REFUND = 2;
    const CLOSE_TYPE_TIMEOUT = 3;
    const CLOSE_TYPE_BUYER_CANCEL = 4;
    const CLOSE_TYPE_SELLER_CANCEL = 5;
    const CLOSE_TYPE_SELLER_REBATE = 6;
    const CLOSE_TYPE_SELLER_STOCK_OUT = 13;

    public static $closeTypeMap = [
        self::CLOSE_TYPE_NO => '未关闭',
        self::CLOSE_TYPE_EXPIRE => '过期关闭',
        self::CLOSE_TYPE_REFUND => '标记退款',
        self::CLOSE_TYPE_TIMEOUT => '超时订单取消',
        self::CLOSE_TYPE_BUYER_CANCEL => '买家取消',
        self::CLOSE_TYPE_SELLER_CANCEL => '卖家取消',
        self::CLOSE_TYPE_SELLER_REBATE => '部分退款',
        self::CLOSE_TYPE_SELLER_STOCK_OUT => '商品缺货无法交易',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->order_no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->order_no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (!$model->order_no) {
                    return false;
                }
            }
            $model->pay_status = self::PAY_STATUS_WAIT;
            $model->order_status = self::ORDER_STATUS_WAIT;
            $model->create_time = time();
            $model->update_time = time();
        });
    }

    /**
     *
     * @return bool|string
     * @throws \Exception
     */
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('order_no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning(__FUNCTION__ . 'find order no failed');

        return false;
    }

}
