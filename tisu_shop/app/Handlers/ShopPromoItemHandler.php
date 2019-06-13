<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/13
 * Time: 15:55
 */

namespace App\Handlers;


use App\Models\BuyerCoupon;
use App\Models\PromoItem;
use App\Models\ShopPromoItem;
use Illuminate\Http\Request;

class ShopPromoItemHandler
{
    public function store(Request $request)
    {
        $goods_ids = $request->get('org_goods_ids');
        $promo_id = $request->get('promo_id');
        $shop_id = $request->get('shop_id');
        $count = ShopPromoItem::where([['promo_id', '=', $promo_id], ['shop_id', '=', $shop_id], ['status', '=', ShopPromoItem::STATUS_ENABLE], ['type', '!=', ShopPromoItem::GOODS_TYPE_MANDATORY]])->get(['goods_id', 'promo_price']);
        if ($count) {
            $buyer_tmp = BuyerCoupon::where([['shop_id', '=', $shop_id], ['promo_id', '=', $promo_id]])->count();
            if ($buyer_tmp == 0) {
                ShopPromoItem::whereNotIn('goods_id', $goods_ids)->where([['shop_id', '=', $shop_id], ['promo_id', '=', $promo_id], ['type', '!=', ShopPromoItem::GOODS_TYPE_MANDATORY]])->update(['status' => ShopPromoItem::STATUS_UNABLE]);
            }
            $goods_list = PromoItem::whereIn('goods_id', $goods_ids)->where('promo_id', $request->get('promo_id'))->get(['goods_id', 'promo_price']);
            $goods_data['type'] = ShopPromoItem::GOODS_TYPE_OPTIONAL;
            $goods_data['promo_id'] = $promo_id;
            $goods_data['shop_id'] = $shop_id;
            $goods_data['status'] = ShopPromoItem::STATUS_ENABLE;
            foreach ($goods_list as $key => $val) {
                $goods_data['goods_id'] = $val['goods_id'];
                $goods_data['promo_price'] = $val['promo_price'];
                ShopPromoItem::firstOrCreate($goods_data);
            }

        } else {
            $goods_list = PromoItem::whereIn('goods_id', $goods_ids)->where('promo_id', $request->get('promo_id'))->get(['goods_id', 'promo_price']);
            foreach ($goods_list as $key => $val) {
                $param[] = [
                    'promo_id' => $promo_id,
                    'shop_id' => $shop_id,
                    'type' => ShopPromoItem::GOODS_TYPE_OPTIONAL,
                    'status' => ShopPromoItem::STATUS_ENABLE,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                    'goods_id' => $val['goods_id'],
                    'promo_price' => $val['promo_price']
                ];
            }
            if (isset($param)) {
                ShopPromoItem::insert($param);
            }
        }
    }
}