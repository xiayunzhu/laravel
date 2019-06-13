<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 16:29
 */

namespace App\Http\Controllers\WeChat;


use App\Handlers\BuyerCouponHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BuyerCoupon\StoreRequest;
use App\Lib\Response\Result;
use App\Models\BuyerCoupon;
use App\Models\Promo;

class BuyerCouponController extends Controller
{
    /**
     * @var BuyerCouponHandler
     */
    private $buyerCouponHandler;

    /**
     * BuyerCouponController constructor.
     * @param BuyerCouponHandler $buyerCouponHandler
     */
    public function __construct(BuyerCouponHandler $buyerCouponHandler)
    {
        $this->buyerCouponHandler = $buyerCouponHandler;
    }

    /**优惠券领取
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $promo_id = $request->get('promo_id');
            $promo = Promo::find($promo_id)->toArray();
            if ($promo) {
                $data = $this->buyerCouponHandler->store($request, $promo);
                $result->succeed($data);
            } else {
                $result->failed('该优惠券不存在');
            }

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();
    }
}