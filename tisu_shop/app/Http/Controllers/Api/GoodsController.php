<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GoodsException;
use App\Handlers\GoodsHandler;
use App\Handlers\CategoryHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Goods\DeleteRequest;
use App\Http\Requests\Api\Goods\GoodSpecStoreRequest;
use App\Http\Requests\Api\Goods\GoodStoreRequest;
use App\Http\Requests\Api\Goods\ListRequest;
use App\Http\Requests\Api\Goods\SpecRequest;
use App\Http\Requests\Api\Goods\UpperLowerRequest;
use App\Http\Requests\Api\Goods\UpdateRequest;
use App\Http\Requests\Api\Goods\DetailRequest;
use App\Lib\Response\Result;
use App\Models\Goods;
use App\Models\Brand;
use App\Models\OrgGood;

/**
 * @group 卖家仓库商品(seller warehouse goods)
 * author:zhaona
 * review_at:2019-05-13
 *
 * Class GoodsController
 * @package App\Http\Controllers\Api
 */
class GoodsController extends Controller
{

    /**
     * @var GoodsHandler
     */
    private $goodsHandler;

    public function __construct(GoodsHandler $goodsHandler)
    {
        $this->goodsHandler = $goodsHandler;

    }

    /**
     * A1001仓库商品选款接口(api.goods.list)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam page nullable 页码 Example: 1
     * @queryParam per_page nullable 分页大小 Example: 10
     * @queryParam publish_status nullable 售卖状态 Example: upper
     * @queryParam idsIn nullable 原商品ID集合 Example: [1,9]
     * @queryParam name nullable 商品名字 Example:
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param ListRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->goodsHandler->page($request);
        $data->load(['logo_image']);
        if ($data) {
            $data = $data->toArray();

            $data = fmt_array($data, ['file_url' => 'image_link']);
        }
        $result->succeed($data);

        return response()->json($result->toArray());
    }

    /**
     * A1101-添加到仓库(api.goods.goodStore)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam publish_status nullable 售卖状态 Example: upper
     * @queryParam org_goods_id required 平台商品ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param GoodStoreRequest $request
     * @param Result $result
     * @return array
     */
    public function goodStore(GoodStoreRequest $request, Result $result)
    {
        ## 校验 店铺是否登录者名下
        try {
            $publish_status = $request->get('publish_status');
            $org_good = OrgGood::find($request->get('org_goods_id'));
            $shop_id = $request->get('shop_id');
            if (!$org_good) {
                throw new \Exception('该商品不存在');
            }

            $goodSpecs = $org_good->specs;
            if (!count($goodSpecs)) {
                throw new \Exception('该商品SKU信息不存在');
            }

            $sku_count = $done_sku_count = $new_sku_count = 0;
            foreach ($goodSpecs as $goodSpec) {

                $sku_add_status = $this->goodsHandler->copyFormOrg($shop_id, $goodSpec->id, $publish_status);
                if ($sku_add_status == GoodsHandler::SKU_NEW) {
                    $new_sku_count++;
                } elseif ($sku_add_status == GoodsHandler::SKU_DONE) {
                    $done_sku_count++;
                }
                $sku_count++;
            }

            $result->succeed(['status' => true, 'message' => $new_sku_count . '个SKU商品新增，' . $done_sku_count . '个SKU商品已添加过我的仓库，共' . $sku_count . "个SKU商品"]);

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }


    /**
     * A1003-SKU添加到仓库(api.goods.goodSpecStore)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam org_goods_id required 平台商品ID Example: 1
     * @queryParam org_goods_spec_ids nullable SKU商品ID集合 Example: [1,2]
     * @queryParam publish_status nullable 售卖状态 Example: upper
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param GoodSpecStoreRequest $request
     * @param Result $result
     * @return mixed
     */
    public function goodSpecStore(GoodSpecStoreRequest $request, Result $result)
    {
        ## 校验 店铺是否登录者名下
        try {
            $shop_id = $request->get('shop_id');
            $org_goods_id = $request->get('org_goods_id');
            $org_goods_spec_ids = $request->get('org_goods_spec_ids');
            $publish_status = $request->get('publish_status');


            if (empty($org_goods_spec_ids)) {

                ## 不传sku ID    默认上架该商品所有SKU
                $request = new GoodStoreRequest;
                $request->offsetSet('shop_id', $shop_id);
                $request->offsetSet('org_goods_id', $org_goods_id);
                $request->offsetSet('publish_status', $publish_status);
                $res = $this->goodStore($request, $result);
                return $res;
            } else {

                ## 传sku ID集合
                $sku_count = $done_sku_count = $new_sku_count = 0;
                foreach ($org_goods_spec_ids as $spec_id) {

                    $sku_add_status = $this->goodsHandler->copyFormOrg($shop_id, $spec_id, $publish_status);

                    if ($sku_add_status == GoodsHandler::SKU_NEW) {
                        $new_sku_count++;
                    } elseif ($sku_add_status == GoodsHandler::SKU_DONE) {
                        $done_sku_count++;
                    }
                    $sku_count++;
                }
                $result->succeed(['status' => true, 'message' => $new_sku_count . '个SKU商品新增，' . $done_sku_count . '个SKU商品已添加过我的仓库，共' . $sku_count . "个SKU商品"]);
            }

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }

    /**
     * A1001-A1007上架下架到店铺(api.goods.upper_lower)
     * @queryParam goods_id required 商品ID Example: 1
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
            $res = $this->goodsHandler->upper_lower($request);
            if ($res) {
                $result->succeed($res);
            } else {
                $result->failed('操作失败');
            }
        } catch (\Exception $exception) {
            if ($exception instanceof GoodsException)
                $result->failed($exception->getMessage(), $exception->getCode());
            else
                $result->failed('服务器异常,请稍后尝试');
        }
        return $result->toArray();
    }

    /**
     * 商品删除(api.goods.delete)
     * @queryParam goods_id required 商品ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DeleteRequest $request
     * @param Result $result
     * @return array
     */
    public function delete(DeleteRequest $request, Result $result)
    {
        $id = $request->get('goods_id');
        try {
            $goodsGroup = Goods::find($id);
            if ($goodsGroup) {
                $goodsGroup->delete();
                $result->setMessage('删除成功');
                $result->succeed(true);
            } else {
                $result->failed('已删除');
            }

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1007-商品编辑接口(api.goods.update)
     * @queryParam goods_id required 商品ID Example: 1
     * @queryParam brand_id nullable 品牌ID Example: 1
     * @queryParam category_id nullable 分类ID Example: 1
     * @queryParam goods_group_id nullable 分组ID Example: 1
     * @queryParam name nullable 红人自定义名称 Example: 1
     * @queryParam introduction nullable 商品简介 Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, Result $result)
    {
        try {
            $data = $this->goodsHandler->update($request);
            $result->succeed($data);
        } catch (\Exception $exception) {

            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1007-商品详情(api.goods.detail)
     * @queryParam goods_id required 商品ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->goodsHandler->appDetail($request);
            $data = fmt_array($data, ['file_url' => 'image_link']);

            $result->succeed($data);
        } catch (\Exception $exception) {
            if ($exception instanceof GoodsException)
                $result->failed($exception->getMessage(), $exception->getCode());
            else
                $result->failed('服务器异常,请稍后尝试' . $exception->getMessage());
        }
        return $result->toArray();
    }


    /**
     * A1101-商品分类列表(api.goods.category_list)
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param Result $result
     * @return array
     */
    public function category_list(Result $result)
    {
        $data = CategoryHandler::stairCategories();
        $result->succeed(['options' => $data]);
        return $result->toArray();
    }

    /**
     * A1101-商品品牌列表(api.goods.brand_list)
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param Result $result
     * @return array
     */
    public function brand_list(Result $result)
    {
        $data = Brand::get(['id', 'name']);
        $result->succeed(['options' => $data]);
        return $result->toArray();
    }

    /**
     * 参数选择，商品SKU信息（根据颜色、尺码、商品ID）
     *
     * @param SpecRequest $request
     * @param Result $result
     * @return array
     */
    public function spec(SpecRequest $request, Result $result)
    {
        try {
            $data = $this->goodsHandler->spec($request);
            if ($data) {
                $data = $data->toArray();
                $data = fmt_array($data, ['image_url' => 'image_link']);
            }
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }


}
