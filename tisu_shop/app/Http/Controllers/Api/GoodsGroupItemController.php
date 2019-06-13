<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GoodsGroupItemException;
use App\Handlers\GoodsGroupItemHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GoodsGroupItem\DeleteBatRequest;
use App\Http\Requests\Api\GoodsGroupItem\DeleteRequest;
use App\Http\Requests\Api\GoodsGroupItem\ListRequest;
use App\Http\Requests\Api\GoodsGroupItem\StoreRequest;
use App\Http\Requests\Api\GoodsGroupItem\UpdateRequest;
use App\Lib\Response\Result;
use App\Models\GoodsGroupItem;

/**
 * @group 分组商品管理(goods of grouping management)
 * author:zhaona
 * review_at:2019-05-14
 *
 * Class GoodsGroupItemController
 * @package App\Http\Controllers\Api
 */
class GoodsGroupItemController extends Controller
{
    private $goodsGroupItemHandler;

    public function __construct(GoodsGroupItemHandler $goodsGroupItemHandler)
    {
        $this->goodsGroupItemHandler = $goodsGroupItemHandler;
    }

    /**
     * 分组商品列表(api.goods_groups_item.list)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam page nullable 页码 Example: 1
     * @queryParam per_page nullable 分页大小 Example: 10
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->goodsGroupItemHandler->page($request);
        if ($data) {
            $data->load(['goods', 'goods.logo_image']);
            $data = $data->toArray();
            $data = fmt_array($data, ['file_url' => 'image_link']);
        }

        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * 添加分组商品(api.goods_groups_item.store)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam goods_group_id required 分组ID Example: 1
     * @queryParam goods_ids required 商品ID集合 Example: [1,2]
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $goods_ids = $request->get('goods_ids');

            $row = $request->only(GoodsGroupItem::$fields);
            foreach ($goods_ids as $value) {
                $row['goods_id'] = $value;
                $count = GoodsGroupItem::where($row)->count();
                if ($count) {
                    throw new GoodsGroupItemException('ID为【' . $value . '】的商品已加入分组', 10006);
                }
                GoodsGroupItem::create($row);
            }
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, int $id, Result $result)
    {
        try {
            $data = $this->goodsGroupItemHandler->update($request, $id);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 删除分组商品(api.goods_groups_item.delete)
     * @queryParam id required 分组ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DeleteRequest $request
     * @param Result $result
     * @return array
     */
    public function delete(DeleteRequest $request, Result $result)
    {
        try {
            $this->goodsGroupItemHandler->delete($request->get('id'));
            $result->setMessage('删除成功');
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * 批量删除(api.goods_groups_item.destroyBat)
     * @queryParam ids required 分组ID集合 Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DeleteBatRequest $request
     * @param Result $result
     * @return array
     */
    public function destroyBat(DeleteBatRequest $request, Result $result)
    {
        try {
            $ids = $request->get('ids');
            if ($ids && is_array($ids)) {
                $dels = $this->goodsGroupItemHandler->destroyBat($ids);
                if ($dels > 0) {
                    $result->succeed(true);
                } else {
                    $result->failed('删除失败');
                }
            } else {
                $result->failed('参数错误');
            }
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
}
