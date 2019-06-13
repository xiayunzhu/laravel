<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 16:36
 */

namespace App\Http\Controllers\Api;


use App\Handlers\BuyerCouponHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BuyerCoupon\ListRequest;
use App\Lib\Response\Result;
use Illuminate\Http\Request;

class BuyerCouponController extends Controller
{
    private $buyercouponHandler;

    public function __construct(BuyerCouponHandler $buyerCouponHandler)
    {
        $this->buyercouponHandler = $buyerCouponHandler;
    }

    public function list(ListRequest $request, Result $result)
    {
        $data = $this->buyercouponHandler->page($request);
        return $result->succeed($data)->toArray();
    }

}