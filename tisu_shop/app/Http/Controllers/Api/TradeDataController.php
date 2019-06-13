<?php

namespace App\Http\Controllers\Api;

use App\Handlers\OrderHandler;
use App\Http\Requests\Api\Trade\ListRequest;
use App\Http\Requests\Api\Trade\StatisticsRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;
/**
 * @group 交易数据管理
 * author:ysc
 * review_at:2019-05-11
 */

class TradeDataController extends Controller
{
    protected $handler;

    public function __construct(OrderHandler $orderHandler)
    {
        $this->handler = $orderHandler;
    }

    /**
     * 交易数据  (api.tradeData.trade)
     *
     * @queryParam __debugger int  required 测试账号 Example: 1
     * @queryParam  shop_id int required 店铺id Example: 1
     * @queryParam  begin_time string required 开始时间 Example:2019-04-17
     * @queryParam  end_time string required 结束时间 Example:2019-04-23
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function trade(ListRequest $request, Result $result)
    {
        try {
            $data = $this->handler->trade($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     *记录每天的浏览量  (api.tradeData.recordPageView)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  shop_id int required 店铺id Example:1
     * @queryParam  time string required 日期 Example:2019-04-17
     * @queryParam  page_view int required 浏览量 Example:10
     * @param StatisticsRequest $request
     * @param Result $result
     * @return array
     */
    public function recordPageView(StatisticsRequest $request, Result $result)
    {
        try {
            $model = $this->handler->recordPageView($request);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 交易统计数据 (api.tradeData.tradeStatistics)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  shop_id int required 店铺id Example:1
     * @queryParam  begin_time string required 开始时间 Example:2019-04-17
     * @queryParam  end_time string required 结束时间 Example:2019-04-23
     * @param ListRequest $request
     * @param Result $result
     * @return array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function tradeStatistics(ListRequest $request, Result $result)
    {
        try {
            $data = $this->handler->tradeStatistics($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 营业额数据 (api.tradeData.turnover)
     *
     * @queryParam __debugger int required 测试账号 Example:1
     * @queryParam  shop_id int required 店铺id Example:1
     * @queryParam  begin_time string required 开始时间 Example:2019-04-17
     * @queryParam  end_time string required 结束时间 Example:2019-04-23
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function turnover(ListRequest $request, Result $result)
    {
        try {
            $data = $this->handler->turnover($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
