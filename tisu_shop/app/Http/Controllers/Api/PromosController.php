<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PromosException;
use App\Handlers\BuyerCouponHandler;
use App\Handlers\PromoHandler;
use App\Handlers\PromoItemHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Promo\DeleteRequest;
use App\Http\Requests\Api\Promo\EffectRequest;
use App\Http\Requests\Api\Promo\ItemRequest;
use App\Http\Requests\Api\Promo\ListRequest;
use App\Http\Requests\Api\Promo\StoreRequest;
use App\Lib\Response\Result;
use App\Models\Promo;
use App\Models\PromoItem;
use App\Models\PromoShop;


class PromosController extends Controller
{
    private $promoHandler;
    private $buyerCouponHandler;
    private $promoItemHandler;

    public function __construct(PromoHandler $promoHandler, PromoItemHandler $promoItemHandler)
    {
        $this->promoHandler = $promoHandler;
        $this->promoItemHandler = $promoItemHandler;
    }

    /**
     * @param DeleteRequest $request
     * @param Result $result
     * @return array
     */
    public function promoItem(DeleteRequest $request,Result $result){
        try {
            $data = $this->promoItemHandler->page($request);
            $result->succeed($data);

        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
  public function addItem(ItemRequest $request,Result $result){
      try {
          $data = $this->promoItemHandler->add($request);
          $result->succeed($data);

      } catch (\Exception $exception) {

          $result->failed($exception->getMessage(), $exception->getCode());
      }

      return $result->toArray();
  }
}
