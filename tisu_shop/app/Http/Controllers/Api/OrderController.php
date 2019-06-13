<?php

namespace App\Http\Controllers\Api;

use App\Handlers\OrderHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\AddressRequest;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Http\Requests\Api\Order\DetailRequest;
use App\Http\Requests\Api\Order\ListAppRequest;
use App\Http\Requests\Api\Order\ListRequest;
use App\Http\Requests\Api\Order\OrderPriceRequest;
use App\Lib\Response\Result;

/**
 * @group 订单管理
 * author:ysc
 * review_at:2019-05-11
 */
class OrderController extends Controller
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
     *
     *
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
     * 订单列表 (api.orders.appList)
     *
     * @queryParam __debugger int required 测试账号 Example: 1
     * @queryParam shop_id int required 店铺id Example:1
     * @queryParam order_status string  订单状态: WAIT：待付款，PAYED：待发货，SEND：已发货，SIGN：确认收货，CLOSE：已关闭 Example:WAIT
     * @queryParam order_no string 订单号 Example:20190510102534665676
     * @queryParam page int 页码  Example:1
     * @queryParam per_page int 分页大小 Example:20
     * @param ListAppRequest $request
     * @param Result $result
     * @return array
     */

    public function appList(ListAppRequest $request, Result $result)
    {
        $data = $this->orderHandler->appPage($request);
        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 订单详情  (api.orders.detail)
     *
     * @queryParam id int required 订单id Example:1
     * @queryParam __debugger int required 测试账号 Example: 1
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->orderHandler->order_detail($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 修改收货地址 (api.orders.update_address)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam id int required 订单id Example:1
     * @queryParam seller_msg string 卖家备注 Example:测试
     * @queryParam mobile string 手机号 Example:13011112222
     * @queryParam receiver string 收货人 Example:小七
     * @queryParam province string 省 Example:浙江省
     * @queryParam city string 市 Example:杭州市
     * @queryParam district string 区 Example:江干区
     * @queryParam detail string 详细地址 Example:九合路
     * @queryParam zip_code string 邮编 Example:0000
     * @param AddressRequest $request
     * @param Result $result
     * @return array
     */
    public function update_address(AddressRequest $request, Result $result)
    {
        try {
            $data = $this->orderHandler->update_address($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 修改订单价格
     *
     * @param OrderPriceRequest $request
     * @param Result $result
     * @return array
     */
    public function update_price(OrderPriceRequest $request, Result $result)
    {
        try {
            $data = $this->orderHandler->update_price($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 取消订单  (api.orders.cancel_order)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam id int required 订单id Example:1
     * @param CancelOrderRequest $request
     * @param Result $result
     * @return array
     */
    public function cancel_order(CancelOrderRequest $request, Result $result)
    {
        try {
            $data = $this->orderHandler->cancel_order($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
