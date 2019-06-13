<?php

namespace App\Http\Controllers\WeChat;

use App\Handlers\RefundHandler;
use App\Http\Requests\WeChat\Refund\DetailRequest;
use App\Http\Requests\WeChat\Refund\RefundLogisticsRequest;
use App\Http\Requests\WeChat\Refund\SaleAfterRequest;
use App\Models\Refund;
use Ml\Response\Result;
use App\Http\Requests\WeChat\Refund\StoreRequest;
use App\Http\Requests\WeChat\Refund\ListRequest;
use App\Http\Controllers\Controller;


class RefundsController extends Controller
{

    private $refundHandler;

    public function __construct(RefundHandler $refundHandler)
    {
        $this->refundHandler = $refundHandler;
    }

    /**
     * 退款列表
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     * @throws \App\Exceptions\RefundsException
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->refundHandler->page($request);
        $data->load(['order_item']);
        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 详情
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result){
        try {
            $data = $this->refundHandler->wx_detail($request);
            if ($data) {
                $data = $data->toArray();
                $data['end_time'] = 86400 + strtotime($data['created_at']);
                $data = fmt_array($data, ['image_url' => 'image_link']);
            }
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();

    }

    /**
     * 申请退款
     *
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->store($request);
            if ($data)
                $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }

    /**
     * 退款原因列表
     *
     * @param Result $result
     * @return array
     */
    public function refund_reason_list( Result $result)
    {
        $data = Refund::$refundReasons;
        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 处理方式列表
     *
     * @param Result $result
     * @return array
     */
    public function refund_way_list( Result $result)
    {
        $data = Refund::$refundWays;
        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 售后介入
     *
     * @param SaleAfterRequest $request
     * @param Result $result
     * @return array
     */
    public function after_sales(SaleAfterRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->after_sales($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 小程序撤销退款
     *
     * @param SaleAfterRequest $request
     * @param Result $result
     * @return array
     */
    public function undo_sales(SaleAfterRequest $request, Result $result){
        try {
            $data = $this->refundHandler->undo_sales($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 物流信息填写
     *
     * @param RefundLogisticsRequest $request
     * @param Result $result
     * @return array
     */
    public function refund_logistics(RefundLogisticsRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->refund_logistics($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 收货地址
     *
     * @param Result $result
     * @return array
     */
    public function refund_address( Result $result){

        $address = config('bs.order.refund_address');
        $result->succeed($address);
        return $result->toArray();
    }

}
