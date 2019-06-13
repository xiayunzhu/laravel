<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/13
 * Time: 14:22
 */

namespace App\Http\Controllers\Api;


use App\Handlers\PromoItemHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromoItem\ListRequest;
use App\Lib\Response\Result;
use Illuminate\Http\Request;


class PromoItemController extends Controller
{
    private $promoItemHandler;

    public function __construct(PromoItemHandler $promoItemHandler)
    {
        $this->promoItemHandler = $promoItemHandler;

    }

    /**
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request,Result $result){
        try {
            $data = $this->promoItemHandler->page($request);
            $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();

    }

    /**上架
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function upper(Request $request,Result $result){
        try {
            $data = $this->promoItemHandler->upper($request);
            $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
}