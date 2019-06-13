<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 12:52
 */

namespace App\Handlers;


use App\Models\BuyerCoupon;
use App\Models\Promo;
use App\Models\PromoItem;
use App\Models\ShopPromo;
use App\Models\ShopPromoItem;
use Illuminate\Http\Request;

class PromoShopHandler
{
    private $goodsHandler;

    public function __construct(GoodsHandler $goodsHandler)
    {
        $this->goodsHandler = $goodsHandler;
    }

    /**
     * @param $data
     * @param Request $request
     */
    public function store($data, Request $request)
    {
        if ($data['range'] == Promo::GOOD_RANGE_PART_CAN) {
            $shop_id = $request->get('shop_id');
            $promo_shop = ShopPromo::where([['shop_id', '=', $shop_id], ['promo_id', '=', $data['id']], ['status', '=', ShopPromo::STATUS_ENABLE]])->first();
            if (!$promo_shop) {
                $shop_promo['promo_id'] = $data['id'];
                $shop_promo['total_count'] = $data['total_count'];
                $shop_promo['shop_id'] = $shop_id;
                $shop_promo['status'] = ShopPromo::STATUS_ENABLE;
                ShopPromo::create($shop_promo);
                $item_list = PromoItem::where([['promo_id', '=', $data['id']], ['status', '=', PromoItem::STATUS_ENABLE], ['type', '=', PromoItem::GOODS_TYPE_MANDATORY]])->get(['goods_id', 'promo_price']);
                foreach ($item_list as $key => $val) {
                    $param[] = [
                        'promo_id' => $data['id'],
                        'shop_id' => $shop_id,
                        'type' => ShopPromoItem::GOODS_TYPE_MANDATORY,
                        'status' => ShopPromoItem::STATUS_ENABLE,
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                        'goods_id' => $val['goods_id'],
                        'promo_price' => $val['promo_price']
                    ];
                    $goods_id[] = $val['goods_id'];
                }
                if (isset($param)) {

                    ShopPromoItem::insert($param);
                }
            } else {
                $item_list = PromoItem::where([['promo_id', '=', $data['id']], ['status', '=', PromoItem::STATUS_ENABLE], ['type', '=', PromoItem::GOODS_TYPE_MANDATORY]])->get(['goods_id', 'promo_price'])->toArray();
                $buyer_tmp = BuyerCoupon::where([['shop_id', '=', $shop_id], ['promo_id', '=', $data['id']]])->count();
                $goods_ids = array_column($item_list, 'goods_id');
                if ($buyer_tmp == 0) {
                    ShopPromoItem::whereNotIn('goods_id', $goods_ids)->where([['shop_id', '=', $shop_id], ['promo_id', '=', $data['id']], ['type', '!=', ShopPromoItem::GOODS_TYPE_MANDATORY]])->update(['status' => ShopPromoItem::STATUS_UNABLE]);
                }
                $goods_data['type'] = ShopPromoItem::GOODS_TYPE_MANDATORY;
                $goods_data['promo_id'] = $data['id'];
                $goods_data['shop_id'] = $shop_id;
                $goods_data['status'] = ShopPromoItem::STATUS_ENABLE;
                foreach ($item_list as $key => $val) {
                    $goods_data['goods_id'] = $val['goods_id'];
                    $goods_data['promo_price'] = $val['promo_price'];
                    ShopPromoItem::firstOrCreate($goods_data);
                    $goods_id[] = $val['goods_id'];
                }

            }

            if (count($goods_id)>0) {
                $this->goodsHandler->goodAdd($shop_id,$goods_id);
            }
        }
    }
}