<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 12:44
 */

namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\GoodsSpec;
use App\Models\OrgGoodsSpec;
use Illuminate\Http\Request;

class GoodsSpecHandler
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];

        foreach (GoodsSpec::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return GoodsSpec::create($row);
    }

    /**
     * 卖家编辑商品
     * @param Request $request
     * @return mixed
     * @throws GoodsException
     */
    public function sellerEdit(Request $request)
    {
        $id = $request->get('id');
        $data = $request->only(['goods_price', 'line_price', 'virtual_quantity']);
        $goods_spec = GoodsSpec::with('org_goods_spec:id,price_change')->find($id);
        if (!$goods_spec) {
            throw new GoodsException('商品SKU未查询到');
        }
        if ($goods_spec->org_goods_spec) {
            if ($goods_spec->org_goods_spec->price_change == OrgGoodsSpec::PRICE_CHANGE_NO && (isset($data['line_price']) || isset($data['goods_price'])))
                throw new GoodsException('无改价权限');
        }


        foreach ($data as $field => $value) {
            $goods_spec->$field = $value;
        }
        ## 判断 划线价 比 标价低 提示异常
        if ($goods_spec->line_price < $goods_spec->goods_price) {
            throw new GoodsException('划线价格不能低于标价');
        }

        $goods_spec->save();

        return true;


    }

    /**
     * 库存占用
     */
    public function stockLock()
    {

    }

    /**
     * 商品SKU上架,商品下架
     * @param Request $request
     * @return bool
     * @throws GoodsException
     */
    public function upper_lower(Request $request)
    {
        #@todo 校验权限-是否是自己的商品
        $goods_specs_id = $request->get('goods_specs_id');
        $handle = $request->get('handle');

        $goods_specs = GoodsSpec::find($goods_specs_id);
        if ($goods_specs) {


            if ($handle == 'upper')
                $goods_specs->publish_status = GoodsSpec::PUBLISH_STATUS_UPPER;
            elseif ($handle == 'lower')
                $goods_specs->publish_status = GoodsSpec::PUBLISH_STATUS_LOWER;

            $res = $goods_specs->save();

            if ($res)
                return true;
            else
                throw new GoodsException('操作失败');
        } else {
            throw new GoodsException('SKU商品信息未查询到', 10002);
        }
    }

    /**
     * A1011-商品编辑(卖家)接口
     * @param Request $request
     * @return mixed
     * @throws GoodsException
     */
    public function detail(Request $request)
    {
        $goods_specs_id = $request->get('id');

        $goods_spec = GoodsSpec::where('id', $goods_specs_id)->with(['goodSpecs', 'org_goods_spec:id,price_change'])->first(['id', 'org_goods_specs_id', 'fx_price', 'retail_price', 'sold_num', 'quantity', 'line_price', 'goods_price', 'virtual_quantity']);
        if (!$goods_spec)
            throw new GoodsException('商品信息未查询到', 10002);

        return $goods_spec;
    }
}