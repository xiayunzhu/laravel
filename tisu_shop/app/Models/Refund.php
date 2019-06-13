<?php

namespace App\Models;


class Refund extends BaseModel
{
    public static $fields = ['order_no', 'item_no','user_id','goods_id','goods_spec_id','refund_no','refund_way','refund_reason','back_money','phone','remark','image_urls','status','apply_time','arrive_time'];

    ## 退款状态  0：申请中；1：已同意；2：已拒绝；3：已关闭
    const REFUND_REFUNDING = 0;
    const REFUND_REFUND = 1;
    const REFUND_REFUSE = 2;
    const REFUND_CLOSE = 3;
    public static $hasRefundMap = [
        self::REFUND_REFUNDING => '申请中',
        self::REFUND_REFUND => '已同意',
        self::REFUND_REFUSE => '已拒绝',
        self::REFUND_CLOSE => '已关闭',
    ];

    ## 处理方式   0：换货；1：退款；2：退款退货
    const  REFUND_WAY_GOOD = 0;
    const  REFUND_WAY_MONEY = 1;
    const  REFUND_WAY_MONEY_GOOD = 2;
    public static $refundWayMap = [
        self::REFUND_WAY_GOOD => '仅换货',
        self::REFUND_WAY_MONEY => '仅退款',
        self::REFUND_WAY_MONEY_GOOD => '退款退货',
    ];
    public static $refundWays = [
        ['id' => self::REFUND_WAY_GOOD , 'value' => '仅换货'],
        ['id' => self::REFUND_WAY_MONEY , 'value' => '仅退款'],
        ['id' => self::REFUND_WAY_MONEY_GOOD , 'value' => '退款退货'],
    ];

    ## 退款原因    1：未按规定时间发货；2：不合适
    const REFUND_REASON_ONE = 1;
    const REFUND_REASON_TWO = 2;
    public static $refundReasonMap = [
        self::REFUND_REASON_ONE => '未按规定时间发货',
        self::REFUND_REASON_TWO => '不合适',
    ];
    public static $refundReasons = [
        ['id' => self::REFUND_REASON_ONE , 'value' => '未按规定时间发货'],
        ['id' => self::REFUND_REASON_TWO , 'value' => '不合适'],
    ];


    ## 退款操作
    const PROCESS_REFUSE = 'REFUSE';
    const PROCESS_AGREE = 'AGREE';
    const PROCESS_CLOSE = 'CLOSE';
    public static $processMap = [
        self::PROCESS_REFUSE => '拒绝退款',
        self::PROCESS_AGREE => '同意退款',
        self::PROCESS_CLOSE => '关闭退款'
    ];

    ## 退款进度
    const REFUND_PROGRESS_APPLYING = 'APPLYING';
    const REFUND_PROGRESS_SHOP = 'SHOP_DO';
    const REFUND_PROGRESS_BUYER_DELIVER = 'BUYER_DELIVER';
    const REFUND_PROGRESS_SELLER_RECEIVING = 'SELLER_RECEIVING';
    const REFUND_PROGRESS_AFTER_SALE = 'ADMIN_DO';
    const REFUND_PROGRESS_SUCCESS = 'SUCCESS_CLOSE';
    public static $refundProgressMap = [
        self::REFUND_PROGRESS_APPLYING => '提交申请',
        self::REFUND_PROGRESS_SHOP => '商家处理',
        self::REFUND_PROGRESS_BUYER_DELIVER => '买家发货',
        self::REFUND_PROGRESS_SELLER_RECEIVING => '卖家收货',
        self::REFUND_PROGRESS_AFTER_SALE => '售后处理',
        self::REFUND_PROGRESS_SUCCESS => '售后成功',
    ];
    ## APP退款处理：订单状态说明
    public static $refundProgressAPPStatusMap = [
        self::REFUND_PROGRESS_APPLYING => '等待商家处理退款申请',
        self::REFUND_PROGRESS_SHOP => '售后处理中',
        self::REFUND_PROGRESS_AFTER_SALE => '售后已处理',
        self::REFUND_PROGRESS_SUCCESS => '该订单已关闭',
    ];


    ##APP  售后订单处理状态
    public static $refundProgressAPPStatus = [
        self::REFUND_PROGRESS_APPLYING => '待商家处理',
        self::REFUND_PROGRESS_SHOP => '客服处理',
        self::REFUND_PROGRESS_SUCCESS => '已关闭',
    ];

    ##APP  售后订单状态
    public static $refundProgressAPPInfo = [
        self::REFUND_PROGRESS_APPLYING => '申请退款',
        self::REFUND_PROGRESS_SHOP => '客服处理中',
        self::REFUND_PROGRESS_SUCCESS => '已关闭',
    ];


    /**
     * 退款子订单信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_item()
    {
        return $this->hasOne(OrderItem::class, 'item_no', 'item_no')
            ->select(['order_no','item_no', 'goods_name','payment','goods_name','image_url','spec_name','num','goods_price']);
    }


    /**
     * 订单买家信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer(){
        return $this->hasOne(Order::class, 'order_no', 'order_no')
            ->select(['order_no','buyer']);
    }

    /**
     * 订单信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order(){
        return $this->hasOne(Order::class, 'order_no', 'order_no')
            ->select(['order_no','pay_time','total_fee','discount_fee','paid_fee','buyer_msg','send_time','receipt_time','create_time','express_price']);
    }

    /**
     * 含退款子订单的所有订单信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_no', 'order_no')
            ->select('item_no','order_no','has_refund','goods_name','goods_price','spec_name','num','image_url');
    }

    /**
     * SKU商品信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function good_spec(){
        return $this->hasOne(GoodsSpec::class, 'id', 'goods_spec_id')
            ->select(['id','color','size']);
    }

    ## 关闭原因  SELLER_REFUSE：卖家拒绝；SELLER_AGREE：卖家同意，AFTER_SALES：售后拒绝；BUYER_UNDO：买家撤销退款；SELLER_CLOSE：卖家关闭退款；BUYER_TIMEOUT：买家超时不退货;REFUND_SUCCESS:退款成功
    const REFUND_CLOSE_SELLER_REFUSE= 'SELLER_REFUSE';
    const REFUND_CLOSE_SELLER_AGREE= 'SELLER_AGREE';
    const REFUND_CLOSE_AFTER_SALES = 'AFTER_SALES';
    const REFUND_CLOSE_BUYER_UNDO = 'BUYER_UNDO';
    const REFUND_CLOSE_SELLER_CLOSE = 'SELLER_CLOSE';
    const REFUND_CLOSE_BUYER_TIMEOUT = 'BUYER_TIMEOUT';
    const REFUND_CLOSE_REFUND_SUCCESS = 'REFUND_SUCCESS';
    public static $refundCloseMap = [
        self::REFUND_CLOSE_SELLER_REFUSE => '卖家拒绝',
        self::REFUND_CLOSE_SELLER_AGREE=>'卖家同意',
        self::REFUND_CLOSE_AFTER_SALES => '售后拒绝',
        self::REFUND_CLOSE_BUYER_UNDO => '买家撤销退款',
        self::REFUND_CLOSE_SELLER_CLOSE => '卖家关闭退款',
        self::REFUND_CLOSE_BUYER_TIMEOUT => '买家超时不退货',
        self::REFUND_CLOSE_REFUND_SUCCESS => '退款成功',
    ];

    ## 退款次数标识    0：首次；1：多次
    const REFUND_APPLY_ONCE = 0;
    const REFUND_APPLY_MUCH = 1;
    public static $refundAgainApplyMap = [
        self::REFUND_APPLY_ONCE => '首次',
        self::REFUND_APPLY_MUCH => '多次',
    ];

}
