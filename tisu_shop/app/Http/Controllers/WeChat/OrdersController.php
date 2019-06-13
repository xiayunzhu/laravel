<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\OrderException;
use App\Handlers\OrderHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\Order\DetailRequest;
use App\Http\Requests\WeChat\Order\ListRequest;
use App\Http\Requests\WeChat\Order\PayRequest;
use App\Http\Requests\WeChat\Order\StoreRequest;
use App\Lib\Response\Result;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Shop;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * @var OrderHandler
     */
    private $orderHandler;

    public function __construct(OrderHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {

        $data = $this->orderHandler->page($request);
        $data->load(['address', 'order_items']);
        if ($data) {
            $data = $data->toArray();
            $data = fmt_array($data, ['image_url' => 'image_link']);
        }

        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {

            ## 设置订单来源-该接口提供给微信商城使用
            $request->offsetSet('source', Order::SOURCE_WE_CHAT_APPLET);

            ## 设置店铺信息
            $shop = Shop::find($request->get('shop_id'));
            $request->offsetSet('shop_nick', $shop->shop_nick);
            $request->offsetSet('shop_name', $shop->shop_name);

            ## 设置买家信息
            $buyer = Buyer::find($request->get('buyer_id'));
            $request->offsetSet('buyer', $buyer->nick_name);

            $data = $this->orderHandler->createOrder($request);

            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return $result->toArray();
    }



    /**
     * 订单支付
     * @param PayRequest $request
     * @param Result $result
     * @return array
     */
    public function pay(PayRequest $request, Result $result)
    {
        try {
            $order = $this->orderHandler->payOrder($request);
            return $order;
            $result->setMessage('支付成功');
            $result->succeed($order);
        } catch (\Exception $exception) {
            if ($exception instanceof OrderException) {
                $result->failed($exception->getMessage(), $exception->getCode());
            }
        }

        return $result->toArray();
    }

    /**
     * @param PayRequest $request
     * @param Result $result
     * @return array
     */
    public function done(PayRequest $request, Result $result)
    {
        try {
            $order = $this->orderHandler->doneOrder($request);

            $result->setMessage('确认成功');
            $result->succeed($order);
        } catch (\Exception $exception) {
            if ($exception instanceof OrderException) {
                $result->failed($exception->getMessage(), $exception->getCode());
            }
        }

        return $result->toArray();
    }

    /**
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     * @throws OrderException
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $order = $this->orderHandler->detail($request);
            $result->succeed($order);
        } catch (\Exception $exception) {
            if ($exception instanceof OrderException) {
                $result->failed($exception->getMessage(), $exception->getCode());
            }
        }

        return $result->toArray();
    }
}
