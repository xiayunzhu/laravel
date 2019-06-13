<?php

namespace App\Http\Controllers\Api;

use App\Handlers\BuyerHandler;
use App\Handlers\OrderHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Buyer\ListRequest;
use App\Http\Requests\Api\Buyer\OrderRequest;
use App\Http\Requests\Api\Buyer\UpdateRequest;
use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Lib\Response\Result;

class BuyerController extends Controller
{
    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['phone', 'nick_name', 'remark'];

    /**
     *
     * @var BuyerHandler
     */
    private $buyerHandler;

    public function __construct(BuyerHandler $buyerHandler)
    {
        $this->buyerHandler = $buyerHandler;

    }

    /**
     * 买家列表
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $user = $request->user('api');
        //校验店铺归属
        $data = $this->buyerHandler->page($request);

        return $result->succeed($data)->toArray();
    }

    /**
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function info(Request $request, Result $result)
    {
        //增加数据安全校验

        //查询数据
        try {
            $buyerId = $request->get('buyer_id');
            $buyer = Buyer::where('id', $buyerId)->with(['user', 'user.order'])->first();
            if (!$buyer) {
                return $result->failed('信息未查到')->toArray();
            }
            if($buyer->user!=''&&$buyer->user->order!=''){
                $order = $buyer->user->order;

                $count = count($order);
                $sum = 0;
                foreach ($order as $v) {
                    $sum = $sum + $v['total_fee'];
                }
                $avg = $sum / $count;
                $buyer->sum = $sum;
                $buyer->count = $count;
                $buyer->avg = $avg;
                unset($buyer->user);
            }else{
                $buyer->sum = 0;
                $buyer->count =0;
                $buyer->avg =0;
            }
            $result->succeed($buyer);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * @param OrderRequest $request
     * @param Result $result
     * @param OrderHandler $order
     * @return array
     */
    public function orderList(OrderRequest $request, Result $result, OrderHandler $order)
    {
        try {
            $buyer_id = $request->get('buyer_id');
            $shop_id = $request->get('shop_id');
            $user = Buyer::where('id', $buyer_id)->with(['user'])->first();
            if (empty($user->user)) {
                return $result->failed('用户不存在')->toArray();
            }
            $user_id = $user->user->id;
            $request = new Request();
            $request->offsetSet('user_id', $user_id);
            $request->offsetSet('shop_id', $shop_id);
            $data = $order->orderInfo($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * @param UpdateRequest $request
     * @param Buyer $buyer
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Buyer $buyer, Result $result)
    {
        $id = $request->get('id');

        try {
            $buyer = $this->buyerHandler->update($request, $id);
            $result->succeed($buyer);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return response()->json($result->toArray());
    }


}
