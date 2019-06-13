<?php

namespace App\Http\Controllers\Api;

use App\Handlers\GoodsSpecHandler;
use App\Http\Requests\Api\GoodsSpec\DetailRequest;
use App\Http\Requests\Api\GoodsSpec\UpdateRequest;
use App\Http\Requests\Api\GoodsSpec\UpperLowerRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;

/**
 * @group 卖家仓库商品(seller warehouse goods)
 * author:zhaona
 * review_at:2019-05-13
 *
 * Class GoodsController
 * @package App\Http\Controllers\Api
 */
class GoodsSpecsController extends Controller
{
    private $goodsSpecHandler;

    public function __construct(GoodsSpecHandler $goodsSpecHandler)
    {
        $this->goodsSpecHandler = $goodsSpecHandler;
    }

    /**
     * A1011-商品SKU编辑(api.goods_spec.update)
     * @queryParam id required 商品SKU 的ID Example: 4
     * @queryParam goods_price nullable 商品售价(标价) Example: 66.9
     * @queryParam line_price nullable    商品划线价（吊牌价） Example: 99.9
     * @queryParam virtual_quantity nullable 虚拟库存 Example: 1002
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, Result $result)
    {
        try {
            $data = $this->goodsSpecHandler->sellerEdit($request);
            $result->succeed($data);
        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }


        return $result->toArray();
    }

    /**
     * A1007-商品SKU上架下架(api.goods_spec.upper_lower)
     * @queryParam goods_specs_id required SKU商品ID Example: 4
     * @queryParam handle required 平台商品ID Example: upper
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param UpperLowerRequest $request
     * @param Result $result
     * @return array
     */
    public function upper_lower(UpperLowerRequest $request, Result $result)
    {
        try {
            $res = $this->goodsSpecHandler->upper_lower($request);
            if ($res) {
                $result->succeed($res);
            } else {
                $result->failed('操作失败');
            }
        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1011-SKU商品详情页面(api.goods_spec.detail)
     * @queryParam id required SKU商品ID Example: 4
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->goodsSpecHandler->detail($request);
            if ($data) {
                $data = fmt_array($data, ['file_url' => 'image_link']);
            }
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
