<?php

namespace App\Handlers;


use App\Events\MessageEvent;
use App\Exceptions\RefundsException;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Refund;
use App\Models\RefundAddresses;
use App\Models\RefundLogistics;
use Illuminate\Http\Request;

class RefundHandler
{

    private $refundAddressHandler;
    private $deliveryRulesHandler;
    private $refundLogisticsHandler;

    public function __construct(RefundAddressHandler $refundAddressHandler, DeliveryRulesHandler $deliveryRulesHandler, RefundLogisticsHandler $refundLogisticsHandler)
    {
        $this->refundAddressHandler = $refundAddressHandler;
        $this->deliveryRulesHandler = $deliveryRulesHandler;
        $this->refundLogisticsHandler = $refundLogisticsHandler;

    }

    protected $refundFiles = ['id', 'order_no', 'item_no', 'user_id', 'refund_progress'];

    protected $refundDetailFields = ['order_no', 'refund_no', 'refund_way', 'refund_reason', 'back_money', 'created_at', 'refund_progress'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws RefundsException
     */
    public function page(Request $request)
    {
        $query = Refund::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (in_array($field, ['shop_id', 'refund_progress', 'order_no'])) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }
        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        if (!$query->first()) {
            throw new  RefundsException('暂无相关数据');
        }
        $query->get();
        $data = $query->paginate($per_page);
        $data = $data->load(['order_item', 'buyer']);
        foreach ($data as $key => $item) {
            if (!empty($item['image_urls']))
                $data[$key]->image_urls = unserialize($item['image_urls']);

            $tmp = [];
            foreach ($data[$key]->image_urls as $kk => $vv) {
                $tmp[] = storage_image_url($vv);
            }
            $data[$key]->image_urls = $tmp;
            $data[$key]->order_item->image_url = storage_image_url($data[$key]->order_item->image_url);
        }
        return $data;

    }

    /**
     * App  售后订单列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function appPage(Request $request)
    {
        $query = Refund::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (in_array($field, ['shop_id', 'refund_progress', 'order_no'])) {
                        if (strpos($field, 'order_no') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }
        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $query->select($this->refundFiles);
        $data = $query->paginate($per_page);
        $data->load(['order_item', 'buyer']);

        if ($data) {
            $data = $data->toArray();
            $data = fmt_array($data, ['image_url' => 'image_link', 'refund_progress' => Refund::$refundProgressAPPInfo]);
        }
        return $data;

    }

    /**
     * 申请退款
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();

        $orderItem = $this->findOrder($request->get('item_no'), $request->get('back_money'));
        $order = Order::where('order_no', $orderItem->order_no)->first();
        $row = $request->only(Refund::$fields);
        $row['order_no'] = $order['order_no'];
        $row['user_id'] = $request->get('user_id');
        $row['buyer_id'] = $request->get('buyer_id');
        $row['goods_id'] = $orderItem['goods_id'];
        $row['goods_spec_id'] = $orderItem['goods_spec_id'];
        $row['refund_no'] = 'RF' . date('ymdHis') . sprintf('%02d', rand(0, 999));
        $row['image_urls'] = serialize($request->get('image_urls'));
        $row['shop_id'] = $order->shop_id;
        $resRefund = Refund::create($row);

        if ($row['refund_way'] == Refund::REFUND_WAY_GOOD) {
            ## 创建收件地址
            $address = $request->only(RefundAddresses::$fields);
            $address['refund_no'] = $resRefund->refund_no;
            $address['buyer_id'] = $row['user_id'];
            $address['shop_id'] = $row['shop_id'];
            $resAddress = $this->refundAddressHandler->store($address);
            if (!$resAddress) {
                \DB::rollBack();
                return false;
            }
        }

        $OIUpdate = $orderItem->update(['has_refund' => OrderItem::HAS_REFUND_REFUND]);     ## 修改子订单状态
        $OUpdate = $order->update(['refund_status' => Order::RECEIPT_STATUS_DONE]);            ## 修改主订单状态

        if ($resRefund && $OIUpdate && $OUpdate) {
            \DB::commit();
            $resRefund->image_urls = unserialize($resRefund->image_urls);

            //通知卖家-有客户申请退款
            event(new MessageEvent($order, Message::TYPE_REFUND));
            return $resRefund;
        }
        \DB::rollBack();
        return false;

    }


    /**
     * @param $item_no
     * @param $back_money
     * @return mixed
     * @throws RefundsException
     */
    public function findOrder($item_no, $back_money)
    {
        $orderItem = OrderItem::where('item_no', $item_no)->first();
        if (!$orderItem)
            throw new RefundsException('该订单不存在');
        $order = Order::where('order_no', $orderItem->order_no)->first();

        ## Refund::$hasRefundMap   退款订单状态    申请中、已同意不可重复申请；已拒绝、已关闭可再次申请
        $refund = Refund::where('item_no', $item_no)->whereIn('status', [Refund::REFUND_REFUNDING, Refund::REFUND_REFUND])->first();
        if ($refund)
            throw new RefundsException('该订单退款' . Refund::$hasRefundMap[$refund->status] . '，请勿重复操作');
        if ($back_money > $orderItem['payment'])
            throw new RefundsException('退款金额不得大于用户实付金额');
        if ($order['order_status'] == Order::ORDER_STATUS_WAIT)
            throw new RefundsException('未支付订单，不可申请退款');

        return $orderItem;
    }


    /**
     * 退款操作详情页
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function detail(Request $request)
    {
        $refund = Refund::where('id', $request->get('id'))->first($this->refundDetailFields);
        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        $refund->load('buyer');

        if ($refund) {
            $refund = $refund->toArray();
            $refund = fmt_array($refund, ['refund_reason' => Refund::$refundReasonMap]);
        }
        return $refund;
    }


    /**
     *  售后订单详情
     *
     * @param Request $request
     * @return array
     * @throws RefundsException
     */
    public function detail_info(Request $request)
    {
        $refund = Refund::where('id', $request->get('id'))->first(['order_no', 'goods_spec_id', 'item_no', 'refund_no', 'refund_way', 'refund_reason', 'back_money', 'created_at', 'refund_progress']);
        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        $refund->load(['buyer', 'order.order_items', 'order.address']);
        $refund['logistics'] = ['address_message' => '[自提柜]已签收，签收人凭取货码签收感谢你的支持', 'time' => '2019-04-03 14:21:59'];
        if ($refund) {
            $refund = $refund->toArray();
            $refund = fmt_array($refund, ['refund_reason' => Refund::$refundReasonMap, 'image_url' => 'image_link',]);
        }
        return $refund;
    }

    /**
     * 退款订单详情页
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function main_detail(Request $request)
    {
        $refund = Refund::where('id', $request->get('id'))->first(['order_no', 'goods_spec_id', 'item_no', 'refund_no', 'refund_way', 'refund_reason', 'back_money', 'created_at', 'refund_progress']);
        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        $refund->refund_way_info = Refund::$refundWayMap[$refund->refund_way];
        $refund->refund_reason_info = Refund::$refundReasonMap[$refund->refund_reason];
        $refund->refund_progress_info = Refund::$refundProgressAPPInfo[$refund->refund_progress];
        $refund->load(['buyer', 'order_item', 'order', 'order.address']);
        $refund->order->pay_time = date('Y-m-d H:i:s', $refund->order->pay_time);
        $refund->order->send_time = date('Y-m-d H:i:s', $refund->order->send_time);
        $refund->order->receipt_time = date('Y-m-d H:i:s', $refund->order->receipt_time);
        $refund->order->close_time = date('Y-m-d H:i:s', $refund->order->close_time);
        ##图片地址
        $refund->order_item->image_url = storage_image_url($refund->order_item->image_url);

        ## 运费计算
        $addressRequest = new \Illuminate\Http\Request();
        $addressInfo = [["goods_spec_id" => $refund->goods_spec_id, 'num' => $refund->order_item->num]];

        $addressRequest->offsetSet('city_name', $refund->order->address->city);
        $addressRequest->offsetSet('order_items', ($addressInfo));
        ## 配送地址在规则

        $innerRegionRules = $this->deliveryRulesHandler->checkAddress($addressRequest);

        ## 计算配送费用
        $express_price = $this->deliveryRulesHandler->refund_calculate_cost($innerRegionRules['total'], $innerRegionRules['rule']);
        $refund->order->express_price = number_format($express_price);   ## 运费
        $refund->order_item->payment = number_format((floatval($refund->order_item->goods_price) * $refund->order_item->num) + floatval($express_price)); ## 总计实付

        $refund = $refund->toArray();
        $refund['logistics'] = ['address_message' => '[自提柜]已签收，签收人凭取货码签收感谢你的支持', 'time' => '2019-04-03 14:21:59'];
        return $refund;
    }


    /**
     * APP 退款处理
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function process(Request $request)
    {
        $refund = Refund::find($request->get('id'));
        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        $orderItem = OrderItem::where('item_no', $refund->item_no)->first();
        ##过滤重复操作
        if(in_array($refund->refund_progress,[Refund::REFUND_PROGRESS_SHOP,Refund::REFUND_PROGRESS_BUYER_DELIVER,Refund:: REFUND_PROGRESS_SELLER_RECEIVING,Refund::REFUND_PROGRESS_AFTER_SALE ,Refund:: REFUND_PROGRESS_SUCCESS ]))
            throw new RefundsException('重复操作');
        switch ($request->get('handle')) {
            ## 商家未处理 交予平台处理
            case Refund::PROCESS_REFUSE:
                ## 商家 拒绝申请  售后成功（退款订单关闭）
                $refund->status = Refund::REFUND_REFUSE;                       //退款状态：已拒绝
                $orderItem->has_refund = OrderItem::HAS_REFUND_REFUSE;         //子订单退款状态：拒绝退款
                $refund->refund_progress = Refund::REFUND_PROGRESS_SUCCESS;
                $refund->seller_audit_time = time();
                $refund->close_reason = Refund::REFUND_CLOSE_SELLER_REFUSE;    //关闭原因：卖家拒绝

                $refuse_reason = $request->only('refuse_reason');
                $refund->refuse_reason = $refuse_reason['refuse_reason'];      //卖家拒绝理由
                break;
            case Refund::PROCESS_AGREE:
                ## 商家 同意申请  1仅退款:售后成功（退款订单关闭） 2退款退货:售后处理
                if($refund->refund_way==Refund::REFUND_WAY_MONEY ){
                    $refund->close_reason = Refund::REFUND_CLOSE_SELLER_AGREE ;    //关闭原因：卖家同意
                    $refund->refund_progress = Refund::REFUND_PROGRESS_SUCCESS;    //退款订单状态：售后成功
                    $refund->seller_audit_time = time();                           //卖家审核时间
                }elseif($refund->refund_way==Refund::REFUND_WAY_MONEY_GOOD){
                    $refund->refund_progress = Refund::REFUND_PROGRESS_SHOP;    //退款订单状态：待售后处理
                }

                $refund->status = Refund::REFUND_REFUND;                       //退款订单状态：已同意
                $orderItem->has_refund = OrderItem::HAS_REFUND_REFUNDING;      //子订单退款状态：同意退款
                break;
            case Refund::PROCESS_CLOSE:
                ## 关闭退款     售后成功（退款订单关闭）
                $refund->status = Refund::REFUND_CLOSE;                        //退款订单状态：已关闭
                $orderItem->has_refund = OrderItem::HAS_REFUND_CLOSE;          //子订单退款状态：关闭退款
                $refund->refund_progress = Refund::REFUND_PROGRESS_SUCCESS;
                $refund->seller_audit_time = time();
                $refund->close_reason = Refund::REFUND_CLOSE_SELLER_CLOSE ;    //关闭原因：卖家关闭退款
                break;
            default:
                throw new RefundsException("handle参数  不能为空");
        }


        \DB::beginTransaction();

        $refundRes = $refund->save();
        $orderItemRes = $orderItem->save();   ## 修改子订单

        if (!$refundRes || !$orderItemRes) {
            \DB::rollback();
            throw new RefundsException("退款操作失败");
        }
        \DB::commit();
        return $refund;
    }

    /**
     * 小程序申请售后
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function after_sales(Request $request)
    {
        $refund = Refund::find($request->get('id'));
        $orderItem = OrderItem::where('item_no', $refund->item_no)->first();

        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");

        ## 售后处理（商家处理、等待客服处理中）
        $refund->refund_progress = Refund::REFUND_PROGRESS_SHOP;
        $orderItem->has_refund = OrderItem::HAS_REFUND_REFUND;


        \DB::beginTransaction();

        $refundRes = $refund->save();
        $orderItemRes = $orderItem->save();   ## 修改子订单

        if (!$refundRes || !$orderItemRes) {
            \DB::rollback();
            throw new RefundsException("申请客服失败");
        }
        \DB::commit();
        return $refund;
    }

    /**
     * 小程序撤销申请
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function undo_sales(Request $request)
    {
        $refund = Refund::find($request->get('id'));
        $orderItem = OrderItem::where('item_no', $refund->item_no)->first();

        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");


        ## 撤销申请   退款订单关闭，子订单关闭退款？？？
        $refund->status = Refund::REFUND_CLOSE;
        $orderItem->has_refund = OrderItem::HAS_REFUND_CLOSE;


        \DB::beginTransaction();

        $refundRes = $refund->save();
        $orderItemRes = $orderItem->save();   ## 修改子订单

        if (!$refundRes || !$orderItemRes) {
            \DB::rollback();
            throw new RefundsException("申请客服失败");
        }
        \DB::commit();
        return $refund;
    }

    /**
     * 填写物流信息
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function refund_logistics(Request $request)
    {

        $refund = Refund::find($request->get('refund_id'));

        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        if ($refund->refund_progress == Refund::REFUND_PROGRESS_BUYER_DELIVER)
            throw new RefundsException("买家已发货请勿重复操作");

        \DB::beginTransaction();


        $refund_logistic = $request->only(RefundLogistics::$fields);
        $res = $this->refundLogisticsHandler->store($refund_logistic);

        $refund->refund_progress = Refund::REFUND_PROGRESS_BUYER_DELIVER; ## 买家发货
        $refund_res = $refund->update();
        if (!$refund_res || !$res) {
            \DB::rollback();
            throw new RefundsException("物流信息填写失败");
        }
        \DB::commit();
        return $res;
    }

    /**
     * WX-退款详情
     *
     * @param Request $request
     * @return mixed
     * @throws RefundsException
     */
    public function wx_detail(Request $request)
    {
        $refund = Refund::where('id', $request->get('id'))->first(['id', 'goods_spec_id', 'refund_progress', 'remark', 'created_at', 'back_money', 'item_no']);
        if (!$refund)
            throw new RefundsException("该笔退款信息不存在");
        $refund->load(['good_spec', 'order_item']);

        return $refund;
    }
}