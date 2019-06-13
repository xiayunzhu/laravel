<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/18
 * Time: 16:21
 */

namespace App\Handlers;

use App\Models\Goods;
use App\Models\GoodsSpec;
use App\Exceptions\DeliveryRulesException;
use App\Models\OrgGood;

class DeliveryRulesHandler
{
    /**
     * 验证用户收货地址是否存在运费规则中
     *
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function checkAddress($request)
    {
        $city_name = $request->get('city_name');
        $total = 0;
        $city = RegionsHandler::region_id($city_name); # 城市信息

        $order_items = $request->get('order_items');
        foreach ($order_items as $key => $order_item) {
            ## 查询商品信息;
            $goodsSpec = GoodsSpec::find($order_item['goods_spec_id']);
            if (!$goodsSpec) {
                throw new DeliveryRulesException('商品不存在或已下架');
            }
            ## 商品规格信息及运费规则
            $goods = Goods::find($goodsSpec->goods_id);
            $good_delivery = OrgGood::where('id', $goods->org_goods_id)->with(['delivery_rules'])->first()->delivery_rules->toArray();
            foreach ($good_delivery as $kk => $item) {
                $tmpIds = explode(',', $item['region']);
                if (in_array($city['id'], $tmpIds))
                    $rule[$key] = $good_delivery[$kk];
            }
            if (empty($rule[$key]))
                throw new DeliveryRulesException('很抱歉，您的收货地址不在可配送区域范围内！');

            $total += $order_item['num'];
        }
        $realRule = arraySort($rule, 'first_fee', 'desc')[0];
        return ['status' => true, 'rule' => $realRule, 'total' => $total];
    }

    /**
     * 计算运费
     * @param $total
     * @param $rule
     * @return string
     */
    public function calculate_cost($total, $rule)
    {

        if ($total <= $rule['first']) {
            $totalPrice = number_format($rule['first_fee'], 2);
        } else {
            $totalPrice = $rule['first_fee'] + ($total - $rule['first']) * ($rule['additional_fee'] / $rule['additional']);
            $totalPrice = number_format($totalPrice, 2);
        }
        return $totalPrice;
    }

    /**
     * 退款订单详情运费差价
     *
     * @param $total
     * @param $rule
     * @return string
     */
    public function refund_calculate_cost($total,$rule){
        return ($rule['additional_fee'] / $rule['additional']) * $total;
    }


}