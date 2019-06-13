<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\DeliveryRulesException;
use App\Handlers\DeliveryRulesHandler;
use App\Http\Requests\WeChat\DeliveryRules\ListRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;
use App\Models\Goods;

class DeliveryRulesController extends Controller
{
    private $deliveryRulesHandler;

    public function __construct(DeliveryRulesHandler $deliveryRulesHandler)
    {
        $this->deliveryRulesHandler = $deliveryRulesHandler;
    }


    /**
     * 计算运费
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function fare(ListRequest $request, Result $result)
    {
        try {
            ## 验证配送地址是否在规则中
            $innerRegionRules = $this->deliveryRulesHandler->checkAddress($request);

            ## 计算配送费用
            $totalFee['fee'] = $this->deliveryRulesHandler->calculate_cost($innerRegionRules['total'],$innerRegionRules['rule']);
            $result->succeed($totalFee);

        } catch (DeliveryRulesException $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
}
