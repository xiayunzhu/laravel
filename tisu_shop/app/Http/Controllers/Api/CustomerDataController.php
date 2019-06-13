<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/25
 * Time: 14:23
 */

namespace App\Http\Controllers\Api;

use App\Handlers\CustomerDataHandler;
use App\Http\Requests\Api\Shop\BuyerRequest;
use Illuminate\Http\Request;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;

class CustomerDataController extends Controller
{
    /**
     * @var CustomerDataHandler
     */
    private $customerHandler;

    /**
     * CustomerDataController constructor.
     * @param CustomerDataHandler $customerHandler
     */
    public function __construct(CustomerDataHandler $customerHandler)
    {
        $this->customerHandler = $customerHandler;
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function customerData(Request $request, Result $result)
    {

        if ($request->get('shop_id')) {
            $data = $this->customerHandler->data($request);
            $result->succeed($data);
        } else {
            $result->failed('店铺ID不能为空');
        }
        return $result->toArray();
    }

    /**
     * @param BuyerRequest $request
     * @param Result $result
     * @return array
     */
    public function buyerCount(BuyerRequest $request, Result $result)
    {
        try {
            $data = $request->get('data');
            $time = strtotime(date('Y-m-d', $data));
            $endTime = $time + 86399;
            $model = $this->customerHandler->update($request, $time, $endTime);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();

    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function count(Request $request, Result $result)
    {
        $shop_id = $request->get('shop_id');
        if ($shop_id) {
            $TypeCountMap = $this->customerHandler->list($request);
            $result->succeed($TypeCountMap);
        } else {
            $result->failed('店铺ID不能为空');
        }
        return $result->toArray();
    }
}