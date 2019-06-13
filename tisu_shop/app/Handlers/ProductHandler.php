<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/14
 * Time: 16:43
 */

namespace App\Handlers;


use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class ProductHandler
{
    /**
     * 创建商品
     * @param $item
     * @return bool
     */
    public function store($item)
    {
        if (empty($item)) {
            return false;
        }
        if (!isset($item['spec_code'])) {
            return false;
        }
        $product = \DB::transaction(function () use ($item) {
            $product = Product::updateOrCreate(['item_code' => $item['item_code']], $item);

            $product_sku = ProductSku::updateOrCreate(['spec_code' => $item['spec_code']], $item);

            return $product;
        });


        return $product;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = Product::query();

        //查询条件处理
        $fields_allow = ['spec_code', 'item_code', 'bar_code', 'item_name'];
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (!in_array($field, $fields_allow))
                    continue;

                if (!empty($value)) {
                    if (strpos($field, 'name') !== false || strpos($field, 'spec_code') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : 20;
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100

        $query->orderBy('spec_code', 'desc');
        $query->orderBy('id', 'desc');


        $data = $query->with(['skus','skus.stock'])->paginate($per_page);

        return $data;
    }
}