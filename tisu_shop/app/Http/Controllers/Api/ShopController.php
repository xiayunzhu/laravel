<?php

namespace App\Http\Controllers\Api;

use App\Handlers\CustomerDataHandler;
use App\Handlers\ShopHandler;
use App\Http\Requests\Api\Shop\BuyerRequest;
use App\Http\Requests\Api\Shop\InfoRequest;
use App\Http\Requests\Api\Shop\UpdateRequest;
use App\Models\Buyer;
use App\Models\CustomerData;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Response\Result;

class ShopController extends Controller
{
    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['shop_nick', 'shop_name', 'icon_url', 'introduction'];
    private $shopHandler;
    private $customerDataHandler;

    public function __construct(ShopHandler $shopHandler, CustomerDataHandler $customerDataHandler)
    {
        $this->shopHandler = $shopHandler;
        $this->customerDataHandler = $customerDataHandler;
    }

    /**店铺基本信息
     * @param Request $request
     * @param int $shopId
     * @param Result $result
     * @return array
     */
    public function info(Request $request, Result $result)
    {
        try {
            $shopId = $request->get('shop_id');
            if (empty($shopId)) {
                return $result->failed('ID不能为空')->toArray();
            }
            $shop = Shop::find($shopId);
            if (!$shop) {
                return $result->failed('信息未查到')->toArray();
            }
//            $user = $request->user('api');
//            if ($shop->user_id != $user->id && (!$this->shopHandler->permission($shopId))) {
//
//                return $result->failed('没有查询权限')->toArray();
//            }
        } catch (\Exception $exception) {
            return $result->failed($exception->getMessage());
        }
        return $result->succeed($shop)->toArray();

    }

    /**
     * @param UpdateRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Result $result)
    {

        try {
            $shop = $this->shopHandler->update($request);
            $result->succeed($shop);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return response()->json($result->toArray());
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */

}