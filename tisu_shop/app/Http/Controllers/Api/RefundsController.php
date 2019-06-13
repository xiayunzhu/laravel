<?php

namespace App\Http\Controllers\Api;

use App\Handlers\RefundHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Refund\DetailRequest;
use App\Http\Requests\Api\Refund\ListAppRequest;
use App\Http\Requests\Api\Refund\ProcessRequest;
use App\Http\Requests\Api\Refund\ListRequest;
use App\Lib\Response\Result;
/**
 * @group 售后订单管理
 * author:ysc
 * review_at:2019-05-11
 */

class RefundsController extends Controller
{
    private $refundHandler;

    public function __construct(RefundHandler $refundHandler)
    {
        $this->refundHandler = $refundHandler;
    }

    /**
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->page($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return response()->json($result->toArray());
    }

    /**
     * 售后订单列表 (api.refund.appList)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  shop_id int required 店铺id Example:1
     * @queryParam refund_progress string  维权订单状态【 APPLYING：待商家处理，ADMIN_DO：客服处理，SUCCESS_CLOSE:已关闭】 Example:APPLYING
     * @queryParam order_no string 订单号 Example:20190510102534665676
     * @queryParam page int 页码 Example:1
     * @queryParam per_page int 分页大小 Example:20
     * @param ListAppRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function appList(ListAppRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->appPage($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return response()->json($result->toArray());
    }


    /**
     * 退款操作详情  (api.refund.detail)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  id int required 售后订单id Example:1
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->detail($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }


    /**
     * 退款订单详情  (api.refund.detail_info)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  id int required 售后订单id Example:1
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */

    public function detail_info(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->detail_info($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 退款订单详情页
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function main_detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->main_detail($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 退款操作 (api.refund.refund_process)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  id int required 售后订单id Example:1
     * @queryParam  handle string required 操作处理【REFUSE：拒绝申请、AGREE：同意申请、CLOSE：关闭申请】 Example:REFUSE
     * @queryParam refuse_reason string 卖家拒绝理由:呵呵
     * @param ProcessRequest $request
     * @param Result $result
     * @return array
     */

    public function refund_process(ProcessRequest $request, Result $result)
    {
        try {
            $data = $this->refundHandler->process($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
