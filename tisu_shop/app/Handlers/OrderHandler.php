<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/16
 * Time: 17:57
 */

namespace App\Handlers;

use App\Events\AssetsEvent;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\OrderException;
use App\Jobs\CloseOrder;
use App\Jobs\ReturnStock;
use App\Models\Message;
use App\Events\MessageEvent;
use App\Models\GoodsSpec;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderDatas;
use App\Models\OrderItem;
use App\Models\OrgGoodsSpec;
use App\Models\Turnover;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;


class OrderHandler
{

    /**
     * @var OrderAddressHandler
     */
    private $orderAddressHandler;
    /**
     * @var OrderItemHandler
     */
    private $orderItemHandler;

    private $cartItemHandler;

    public function __construct(OrderAddressHandler $orderAddressHandler, OrderItemHandler $orderItemHandler, CartItemHandler $cartItemHandler)
    {
        $this->orderAddressHandler = $orderAddressHandler;
        $this->orderItemHandler = $orderItemHandler;
        $this->cartItemHandler = $cartItemHandler;
    }

    /**
     * App 查询的字段
     *
     * @var array
     */
    private $orderFields = ['id', 'order_no', 'total_fee', 'order_status', 'create_time', 'buyer', 'shop_id'];

    private $orderDetailFields = ['id', 'order_no', 'order_status', 'create_time', 'pay_time', 'send_time', 'receipt_time', 'total_fee', 'buyer_msg', 'buyer', 'express_price', 'discount_fee', 'paid_fee', 'user_id', 'user_id'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = Order::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'order_status', 'user_id', 'order_no', 'refund_status'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }

        //近期订单
        $recent = $request->get('recent') ?? 0;
        if ($recent) {
            $begin = strtotime('-7 day');
            $end = time();
            $query->whereBetween('create_time', [$begin, $end]);
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);

        return $data;
    }

    /**
     * App 订单列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function appPage(Request $request)
    {
        $query = Order::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'order_status', 'user_id', 'order_no'])) {
                    if (!empty($value)) {
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
        $query->select($this->orderFields);
        $data = $query->paginate($per_page);
        $data->load(['order_items']);

        if ($data) {
            $data = $data->toArray();
            $data = fmt_array($data, ['image_url' => 'image_link', 'create_time' => 'sec', 'order_status' => Order::$orderStatusAPPMap]);
        }


        return $data;
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws OrderException
     */

    public function orderInfo(Request $request)
    {
        $query = Order::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'user_id',])) {
                    $query->where($field, $value);
                }
            }
        }
        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        if (!$query->first()) {
            throw  new OrderException('暂无相关数据');
        }
        $data = $query->paginate($per_page);

        $data->load(['order_items']);

        return $data;
    }


    /**
     * APP  订单详情
     *
     * @param $request
     * @return array
     * @throws OrderException
     */
    public function order_detail($request)
    {
        $order = Order::where('id', $request->get('id'))->first($this->orderDetailFields);
        if (!$order) {
            throw new OrderException('订单不存在');
        }
        $order->load(['address', 'order_items']);

        $order['logistics'] = ['address_message' => '[自提柜]已签收，签收人凭取货码签收感谢你的支持', 'time' => '2019-04-15 14:21:59'];
        if ($order) {
            $order = $order->toArray();
            $order = fmt_array($order, ['image_url' => 'image_link', 'create_time' => 'sec', 'pay_time' => 'sec', 'send_time' => 'sec', 'receipt_time' => 'sec', 'order_status' => Order::$orderStatusAPPMap]);
        }
        return $order;

    }

    /**
     * @param $data
     * @return mixed
     */
    private function store($data)
    {
        $order = new Order();
        foreach (Order::$fields as $field) {
            if (isset($data[$field])) {
                $order->$field = $data[$field];
            }
        }

        $order->save();

        return $order;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createOrder(Request $request)
    {
        $order = \DB::transaction(function () use ($request) {
            $user_id = $request->get('user_id') ?: auth('api')->id();
            $data = $request->only(Order::$fields);
            ## 创建订单主信息
            $order = $this->store($data);

            ## 创建收件地址
            $address = $request->only(OrderAddress::$fields);
            $address['order_no'] = $order->order_no;
            $this->orderAddressHandler->store($address);

            $total_fee = 0;//商品总金额
            $paid_fee = 0;//实付金额

            ## 创建订单明细
            $order_items = $request->get('order_items');
            foreach ($order_items as $key => $order_item) {
                /**
                 * @var GoodsSpec
                 */
                $goodsSpec = GoodsSpec::find($order_item['goods_spec_id']);
                if (!$goodsSpec) {
                    throw new OrderException('商品不存在或已下架');
                }

//                $goodsSpec->goods;
                $orgGoodsSpec = OrgGoodsSpec::find($goodsSpec->org_goods_specs_id);
                if (!$orgGoodsSpec) {
                    throw new OrderException('原商品不存在或已下架');
                }

                $item = $order->order_items()->make([
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'item_no' => $order->order_no . '-' . ($key + 1),
                    'num' => $order_item['num'],
                    'goods_id' => $goodsSpec->goods_id,
                    'goods_name' => $goodsSpec->goods->title,
                    'spec_name' => $goodsSpec->spec_name,
                    'image_url' => $goodsSpec->image_url,
//                    'deduct_stock_type' => $goodsSpec->deduct_stock_type,
//                    'spec_type' => $goodsSpec->spec_type,
                    'spec_code' => $goodsSpec->spec_code,
                    'goods_no' => $goodsSpec->goods_no,
                    'goods_spec_id' => $goodsSpec->id,
                    'org_goods_specs_id' => $goodsSpec->org_goods_specs_id,
                    'goods_price' => $goodsSpec->goods_price,
                    'line_price' => $goodsSpec->line_price,
//                    'weight' => $goodsSpec->weight,
                    'receivable' => $order_item['num'] * $goodsSpec->line_price,
                    'payment' => $order_item['num'] * $goodsSpec->goods_price,
//                    'buyer_id' => $order->buyer_id,
                    'user_id' => $order->user_id,
                    'shop_id' => $order->shop_id,
                    'create_time' => time(),
                    'status' => OrderItem::STATUS_WAIT,
                ]);

                $item->save();

                ##计算商品总金额
                $total_fee += $goodsSpec->goods_price * $order_item['num'];
                $paid_fee += $goodsSpec->goods_price * $order_item['num'];

                ### 库存占用
                $num = (int)$order_item['num'];
                $goodsSpec->decrQty($num);
                $qyt = $orgGoodsSpec->decreaseStock($num);
                if ($qyt <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
                $orgGoodsSpec->save();
            }

            // 更新订单总金额 (运费全免)
            $paid_fee = $total_fee + $order->express_price + $order->service_fee - $order->discount_fee;//应付金额 = 商品金额合计 + 服务费+ 运费 - 优惠金额
            $paid_fee = $paid_fee > 0 ? $paid_fee : 0;
            $order->update(['total_fee' => $total_fee, 'paid_fee' => $paid_fee]);

            // 将下单的商品从购物车中移除
            $goods_spec_ids = collect($order_items)->pluck('goods_spec_id')->all();
            $flushRes = $this->cartItemHandler->flushCartItems($user_id, $goods_spec_ids);

            ##
            $order->load(['address', 'order_items']);// 'order_items.goods', 'order_items.goodsSpec'

            return $order;
        });

        // 这里直接使用 dispatch 函数 - 发布队列任务
        if (config('queue.default') != 'sync') {
//            CloseOrder::dispatch($order)->delay(config('bs.order.wait_pay.ttl'))->onQueue('closeOrder');
            dispatch((new CloseOrder($order, config('bs.order.wait_pay.ttl')))->onQueue('closeOrder'));
//            ReturnStock::dispatch($order, config('bs.order.stock.ttl'))->onQueue('returnStock')->onConnection('database');
            dispatch((new ReturnStock($order, config('bs.order.stock.ttl')))->onQueue('returnStock'));
        }

        return $order;
    }



    /**
     *
     * 支付订单
     *
     * @param Request $request
     * @return mixed
     */
    public function payOrder(Request $request)
    {
        $order = \DB::transaction(function () use ($request) {
            $order_no = $request->get('order_no');
            $order = Order::where('order_no', $order_no)->first();
            if (!$order) {
                throw new OrderException('订单不存在', 100004);
            }

            if ($order->pay_status == Order::PAY_STATUS_DONE) {
                throw new OrderException('订单已支付', 100003);
            }

            ## 改变状态为已支付
            $order->order_status = Order::ORDER_STATUS_PAYED;
            $order->pay_status = Order::PAY_STATUS_DONE;
            $order->pay_time = time();
            $order->update_time = time();
            $order->save();
            ##销售增加
            foreach ($order->order_items as $order_item) {
                $goodsSpec = GoodsSpec::find($order_item['goods_spec_id']);
                if (!$goodsSpec) {
                    throw new OrderException('商品不存在或已下架');
                }

                $orgGoodsSpec = OrgGoodsSpec::find($goodsSpec->org_goods_specs_id);
                if (!$orgGoodsSpec) {
                    throw new OrderException('原商品不存在或已下架');
                }
                $num = $order_item->num;

                $orgGoodsSpec->addSaleNum($num);
                $goodsSpec->addSaleNum($num);
            }

            return $order;
        });

        // 通知-卖家客户已付款
        event(new MessageEvent($order, Message::TYPE_ORDER));
        event(new AssetsEvent($order, Turnover::TYPE_PAY));

        return $order;

    }

    /**
     * 确认收货
     *
     * @param Request $request
     * @return mixed
     * @throws OrderException
     */
    public function doneOrder(Request $request)
    {
        $order_no = $request->get('order_no');

        $order = Order::where('order_no', $order_no)->first();
        if ($order->order_status == Order::ORDER_STATUS_DONE) {
            throw new OrderException('订单已确认收货', 100004);
        }

        ## 改变状态为完结
        $order->order_status = Order::ORDER_STATUS_DONE;
        $order->update_time = time();
        $order->save();


        return $order;

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws OrderException
     */
    public function update_address(Request $request)
    {
        if (empty(Order::find($request->get('id')))) {
            throw new OrderException('没有该订单', 100004);
        };

        $seller_msg = $request->get('seller_msg');
        $order_address = Order::where('id', $request->get('id'))->first(['order_no', 'seller_msg']);
        if (!empty($seller_msg)) {
            $order_address->seller_msg = $seller_msg;
        }
        $order_address->load(['address']);
        $address = $request->only(OrderAddress::$fields);
        foreach ($address as $key => $value) {
            if (!empty($value)) {
                $order_address->address->$key = $value;
            }
        }
        $order_address->save();
        return $order_address;
    }

    /**
     * APP  修改订单价格
     *
     * @param Request $request
     * @return mixed
     * @throws OrderException
     */
    public function update_price(Request $request)
    {
        $order_price = Order::where('id', $request->get('id'))->first();

        ##状态:未付款
        if (in_array($order_price->order_status, [Order::ORDER_STATUS_WAIT])) {

            $order_price->total_fee = $request->get('total_fee');

            $order_price->save();
            return $order_price;
        }
        throw new OrderException("订单状态异常");


    }

    /**
     * APP  取消订单
     *
     * @param $request
     * @return mixed
     * @throws OrderException
     */
    public function cancel_order($request)
    {
        $order = Order::where('id', $request->get('id'))->first();

        ## 状态:未付款  待发货  已完成  交易关闭
        if (in_array($status = $order->order_status, [Order::ORDER_STATUS_WAIT, Order::ORDER_STATUS_PAYED, Order::ORDER_STATUS_SIGN, Order::ORDER_STATUS_CLOSE])) {
            ##主订单状态关闭
            $order->order_status = Order::ORDER_STATUS_CLOSE;
            ##主订单关闭类型 卖家取消
            $order->close_type = Order::CLOSE_TYPE_SELLER_CANCEL;
            //主订单关闭时间
            $order->close_time = time();

            ##子订单状态  关闭
            $orderItem_ids = OrderItem::where('order_no', $order->order_no)->get();

            \DB::beginTransaction();
            $order->save();
            foreach ($orderItem_ids as $key => $item) {
                $item->status = OrderItem:: STATUS_CLOSE;
                $item->save();
            }
            if (!$order || !$item) {
                \DB::rollback();
                throw new OrderException("取消订单失败");
            }

            \DB::commit();
            return $order;
        }
        throw new OrderException("订单状态异常");


    }


    /**
     * WX 订单详情
     *
     * @param Request $request
     * @return array
     * @throws OrderException
     */
    public function detail(Request $request)
    {

        $order = Order::where('order_no', $request->get('order_no'))->first(['order_status', 'express_price', 'express_company', 'order_no', 'paid_fee', 'discount_fee', 'total_fee', 'pay_time', 'send_time', 'receipt_time', 'create_time', 'buyer_msg', 'close_time']);

        if (empty($order))
            throw new OrderException('该订单详情信息不存在');

        $order->load(['address', 'order_items']);
        if ($order) {
            $order = $order->toArray();
            $order = fmt_array($order, ['image_url' => 'image_link', 'create_time' => 'sec', 'pay_time' => 'sec', 'send_time' => 'sec', 'receipt_time' => 'sec']);
        }

        ## 物流虚拟数据
        $order['logistics'] = ['address_message' => '[上海市中转站]发出，下一站杭州签收人凭取货码签收感谢你的支持', 'time' => '2019-04-08 15:21:59'];

        return $order;
    }


    /**
     * 每日数据录入
     *
     * @param $orders
     * @return string
     */

    public function dailyOrderData($orders)
    {
        $res = [
            'turnover_total' => 0,
            'turnover_top' => 0,
            'order_total' => 0,
            'order_count' => 0,
            'order_pay' => 0,
            'order_send' => 0,
            'buyer_order' => [],
            'buyer_pay' => [],
            'time' => 0,
            'shop_id' => 0,
        ];
        $send_num = 0;
        $order_pay_num = 0;
        $order_count = 0;
        foreach ($orders as $order) {
            ##已付款
            if ($order->pay_status == Order::PAY_STATUS_DONE) {
                $res['turnover_total'] += (int)$order['paid_fee'];            ##营业总额
                $res['turnover_top'] = $order['paid_fee'] > $res['turnover_top'] ? (int)$order['paid_fee'] : (int)$res['turnover_top'];  ##单笔最高营业额
                $res['buyer_pay'][] = $order['buyer'];           ##付款人数
                $order_pay_num++;
            }
            $res['order_total'] += $order['paid_fee'];                         ## 下单总金额
            $res['buyer_order'][] = $order['buyer'];                     ## 下单人数
            ##已发货
            if ($order->send_status == Order::SEND_STATUS_DONE) {
                $send_num++;
            }
            $order_count++;
            $res['order_pay'] = $order_pay_num;                 ##付款单量
            $res['order_send'] = $send_num;                     ##发货单量
            $res['order_count'] = $order_count;                 ##下单单量
            $res['shop_id'] = $order['shop_id'];                ##店铺id
            $res['time'] = strtotime(date("Y-m-d", strtotime('-1 day')));
        }

        $res['buyer_order'] = count($res['buyer_order'] != [] ? array_unique($res['buyer_order']) : []);  //下单人数
        $res['buyer_pay'] = count($res['buyer_pay'] != [] ? array_unique($res['buyer_pay']) : []);        //付款人数
        try {
            $model = OrderDatas::create($res);
            return $model;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */

    public function trade($request)
    {
        $begin_time = strtotime($request->get('begin_time'));
        $end_time = strtotime($request->get('end_time'));
        $shop_id = $request->get('shop_id');

        $query = OrderDatas::query();
        $query->where('shop_id', $shop_id);
        $query->whereBetween('time', [$begin_time, $end_time]);
        $query->select(
            \DB::raw('COALESCE(SUM(turnover_total),0) as turnover_total'),
            \DB::raw('COALESCE(SUM(order_total),0) as order_total'),
            \DB::raw('COALESCE(SUM(buyer_order),0) as buyer_order'),
            \DB::raw('COALESCE(SUM(buyer_pay),0) as buyer_pay'),
            \DB::raw('COALESCE(SUM(order_pay),0) as order_pay'),
            \DB::raw('COALESCE(SUM(order_send),0) as order_send'),
            \DB::raw('COALESCE(SUM(order_count),0) as order_count'),
            \DB::raw('COALESCE(SUM(page_view),0) as page_view')
        );
        $data = $query->first();
        $data['percent'] = $data['order_count'] != 0 ? round($data['order_pay'] / $data['order_count'] * 100, 2) . "%" : 0;
        $data['ave_price'] = $data['buyer_pay'] != 0 ? sprintf("%.2f", $data['turnover_total'] / $data['buyer_pay']) : 0;
        $data['begin_time'] = $begin_time;
        $data['end_time'] = $end_time;

        return $data;
    }

    /**
     * 获取营业额数据
     *
     * @param $request
     * @return mixed
     * @throws OrderException
     */
    public function turnover($request)
    {
        $begin_time = strtotime($request->get('begin_time'));
        $end_time = strtotime($request->get('end_time'));
        $shop_id = $request->get('shop_id');

        $query = OrderDatas::query();

        $query->where('shop_id', $shop_id);
        $query->whereBetween('time', [$begin_time, $end_time]);

        $data = $query->select('turnover_total', 'order_count', 'turnover_top', 'time')->get();
        if(count($data)<=0){
            throw new OrderException('暂无数据');
        }

        $turnover_data = $this->operate_days($data, $begin_time, $end_time, '86400');
        return $turnover_data;
    }


    /**
     * 营业额数据处理
     *
     * @param $data
     * @param $begin_time
     * @param $end_time
     * @param $time
     * @return mixed
     */
    public function operate_days($data, $begin_time, $end_time, $time)
    {
        $days_data = range($begin_time, $end_time, $time);
        ##格式化数据
        foreach ($days_data as $k => $v) {
            $turnover[$k] = sprintf("%.2f", 0);
        }
        ##填充数据
        foreach ($data as $key => $item) {
            $index = array_search($item['time'], $days_data);
            $turnover[$index] = sprintf("%.2f", $item['turnover_total']);
            $turnover_top[] = $item['turnover_top'];
            $order_count[] = $item['order_count'];
        }
        $turnover_top = max($turnover_top);
        $res = [
            'turnover' => 0,
            'turnover_total' => 0,
            'turnover_top' => 0,
            'ave_turnover' => 0,
            'begin_time' => 0,
            'end_time' => 0,
        ];
        ##每日的营业额数据
        $res['turnover'] = $turnover;
        ##每日的营业额数据总和
        $res['turnover_total'] = sprintf("%.2f", array_sum($turnover));
        ##单日最高
        $res['turnover_top'] = sprintf("%.2f", $turnover_top);
        ##日均
        $res['ave_turnover'] = array_sum($order_count) != 0 ? sprintf("%.2f", array_sum($turnover) / array_sum($order_count)) : 0.00;
        $res['begin_time'] = $begin_time;
        $res['end_time'] = $end_time;
        return $res;
    }


    /**
     *记录每天的浏览量
     *
     * @param $request
     * @return mixed
     * @throws OrderException
     */
    public function recordPageView($request)
    {
        $shop_id = $request->get('shop_id');
        $time = strtotime($request->get('time'));
        $order_data = OrderDatas::where(['shop_id' => $shop_id, 'time' => $time])->first();
        if (!$order_data) {
            throw new OrderException('未找到记录');
        }
        $page_view = $request->get('page_view');
        $order_data['page_view'] = $page_view;

        $order_data->save();
        return $order_data;
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function tradeStatistics($request)
    {
        $begin_time = strtotime($request->get('begin_time'));
        $end_time = strtotime($request->get('end_time'));
        $shop_id = $request->get('shop_id');

        $query = OrderDatas::query();
        $query->where('shop_id', $shop_id);
        $query->whereBetween('time', [$begin_time, $end_time]);
        $query->select(
            \DB::raw('COALESCE(SUM(turnover_total),0) as turnover_total'),
            \DB::raw('COALESCE(SUM(order_total),0) as order_total'),
            \DB::raw('COALESCE(SUM(buyer_order),0) as buyer_order'),
            \DB::raw('COALESCE(SUM(buyer_pay),0) as buyer_pay'),
            \DB::raw('COALESCE(SUM(order_pay),0) as order_pay'),
            \DB::raw('COALESCE(SUM(order_send),0) as order_send'),
            \DB::raw('COALESCE(SUM(order_count),0) as order_count'),
            \DB::raw('COALESCE(SUM(page_view),0) as page_view')
        );
        $data = $query->first();
        ##全店转化率
        $data['whole_store'] = $data['page_view'] != 0 ? round($data['order_pay'] / $data['page_view'] * 100, 2) . "%" : 0;
        ##浏览-下单转化率
        $data['pv_order'] = $data['page_view'] != 0 ? round($data['order_count'] / $data['page_view'] * 100, 2) . "%" : 0;
        ##下单-付款转化率
        $data['buyer_order_pay'] = $data['order_count'] != 0 ? round($data['order_pay'] / $data['order_count'] * 100, 2) . "%" : 0;

        $data['begin_time'] = $begin_time;
        $data['end_time'] = $end_time;

        return $data;

    }


    /**
     * 营业额一日处理
     *
     * @param $data
     * @param $begin
     * @return mixed
     */
    public function operate_hours($data, $begin)
    {
        $hours = range(0, 23);
        $order_count = 0;
        //格式化数据
        foreach ($hours as $k => $v) {
            $turnover[$k] = 0;
        }
        //填充数据
        foreach ($data as $key => $item) {
            $index = array_search($item->hour < 10 ? substr($item->hour, 1) : $item->hour, $hours);
            $turnover[$index] += $item['paid_fee'];
            $turnover_top[] = $item['paid_fee'];
            $order_count++;
        }
        $turnover_top = max($turnover_top);
        ##每天的营业额数据
        $res['turnover'] = $turnover;
        ##每日的营业额数据总和
        $res['turnover_total'] = sprintf("%.2f", array_sum($turnover));
        ##单日最高
        $res['turnover_top'] = $turnover_top;
        ##日均
        $res['ave_turnover'] = sprintf("%.2f", array_sum($turnover) / $order_count);
        $res['begin_time'] = date('Y-m-d', $begin);
        return $res;
    }
}