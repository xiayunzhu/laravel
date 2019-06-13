<?php

namespace App\Http\Controllers\WeChat;

use App\Handlers\BuyerHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shop\InfoRequest;
use App\Http\Requests\WeChat\Buyer\StoreRequest;
use App\Lib\Response\Result;
use App\Models\Buyer;
use Illuminate\Http\Request;


class BuyerController extends Controller
{
    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['open_id', 'phone', 'union_id', 'nick_name', 'avatar_url', 'gender', 'remark', 'source', 'language', 'country', 'province', 'city', 'address_id', 'shop_id'];

    /**
     * @var BuyerHandler
     */
    private $buyerHandler;

    public function __construct(BuyerHandler $buyerHandler)
    {
        $this->buyerHandler = $buyerHandler;
    }

    //

    /**
     * @param InfoRequest $request
     * @param Result $result
     * @return array
     */
    public function info(InfoRequest $request, Result $result)
    {

        $buyer = $this->buyerHandler->me($request);
        if ($buyer) {
            $result->succeed($buyer);
        } else {
            $result->failed('未查询到客户信息');
        }

        return $result->toArray();
    }

    /**
     * 创建买家
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $model = $this->buyerHandler->store($request);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }
}
