<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 12:02
 */

namespace App\Handlers;


use App\Exceptions\PromoItemException;
use App\Exceptions\PromosException;
use App\Lib\Response\Result;
use App\Models\Promo;
use App\Models\PromoItem;
use Exception;
use Illuminate\Http\Request;

class PromoItemHandler
{
    private $goodsHandler;
    private $orgGoodHandler;
    private $shopPromoItemHandler;

    public function __construct(GoodsHandler $goodsHandler, OrgGoodHandler $orgGoodHandler, ShopPromoItemHandler $shopPromoItemHandler)
    {
        $this->goodsHandler = $goodsHandler;
        $this->orgGoodHandler = $orgGoodHandler;
        $this->shopPromoItemHandler = $shopPromoItemHandler;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws PromoItemException
     */
    public function upper(Request $request)
    {
        $org_ids = $request->get('org_goods_ids');
        if (is_array($org_ids)) {
            $model = \DB::transaction(function () use ($request, $org_ids) {
                $shop_id=$request->get('shop_id');
                $this->shopPromoItemHandler->store($request);
                $this->goodsHandler->goodAdd($shop_id,$org_ids);
                return true;
            }, 1);
            return $model;
        } else {
            throw new PromoItemException('请选择商品');
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $shop_id = $request->get('shop_id');
        $promo_id = $request->get('promo_id');
        $type = $request->get('type');
        if ($shop_id) {
            $goods_ids = PromoItem::where([['promo_id', '=', $promo_id], ['type', '!=', $type]])->get(['goods_id']);
            if ($goods_ids) {
                $request->offsetSet('idsIn', $goods_ids);
                $goods_list = $this->goodsHandler->page($request);
            } else {
                $goods_list = [];
            }
        } else {
            $goods_ids = PromoItem::where([['promo_id', '=', $promo_id], ['type', '!=', $type]])->get()->pluck('goods_id')->toArray();
            if ($goods_ids) {

                $request->offsetSet('idsIn', $goods_ids);
                $goods_list = $this->orgGoodHandler->page($request);
            } else {
                $goods_list = [];
            }

        }
        return $goods_list;
    }
}