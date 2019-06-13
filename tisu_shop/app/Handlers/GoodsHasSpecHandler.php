<?php


namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\GoodsHasSpec;
use Illuminate\Http\Request;

class GoodsHasSpecHandler
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];

        foreach (GoodsHasSpec::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return GoodsHasSpec::create($row);
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
        $data = $request->only(['goods_price', 'line_price', 'quantity', 'sales_num']);
        $goods_spec = GoodsHasSpec::find($id);
        if (!$goods_spec) {
            throw new GoodsException('商品SKU未查询到');
        }


        foreach ($data as $field => $value) {
            $goods_spec->$field = $value;
        }
        ## 判断 划线价 比 标价低 提示异常
        if ($goods_spec->line_price < $goods_spec->goods_price) {
            throw new GoodsException('划线价格不能低于标价');
        }

        $goods_spec->save();

        return $goods_spec;


    }
}