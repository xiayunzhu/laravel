<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 12:29
 */

namespace App\Http\Controllers\Api;


use App\Handlers\ShopEventHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShopEvent\StoreRequest;
use App\Lib\Response\Result;

class ShopEventController extends Controller
{
    /**
     * @var ShopEventHandler
     */
    private $shopEventHandler;

    /**
     * ShopEventController constructor.
     * @param ShopEventHandler $shopEventHandler
     */
    public function __construct(ShopEventHandler $shopEventHandler)
    {
        $this->shopEventHandler = $shopEventHandler;
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $data = $this->shopEventHandler->store($request);
            $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
}