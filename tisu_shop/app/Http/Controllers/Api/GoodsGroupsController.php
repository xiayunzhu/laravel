<?php

namespace App\Http\Controllers\Api;

use App\Handlers\GoodsGroupHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GoodsGroup\DeleteRequest;
use App\Http\Requests\Api\GoodsGroup\DetailRequest;
use App\Http\Requests\Api\GoodsGroup\ListRequest;
use App\Http\Requests\Api\GoodsGroup\StoreRequest;
use App\Http\Requests\Api\GoodsGroup\UpdateRequest;
use App\Lib\Response\Result;

/**
 * @group 商品分组管理(goods grouping management)
 * author:zhaona
 * review_at:2019-05-14
 *
 * Class GoodsGroupsController
 * @package App\Http\Controllers\Api
 */
class GoodsGroupsController extends Controller
{
    private $goodsGroupHandler;

    public function __construct(GoodsGroupHandler $goodsGroupHandler)
    {
        $this->goodsGroupHandler = $goodsGroupHandler;
    }

    /**
     * A1004-分组列表(api.goods_groups.list)
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
        $data = $this->goodsGroupHandler->page($request);

        if ($data) {
            $data->load('items');
            $data = $data->toArray();
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['goods_num'] = count($item['items']);
                unset($data['data'][$key]['items']);
            }
        }

        $result->succeed($data);
        return $result->toArray();
    }

    /**
     * A1004-分组详情(api.goods_groups.detail)
     * @queryParam id required 分组ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $goodsGroup = $this->goodsGroupHandler->detail($request->get('id'));
            $result->succeed($goodsGroup);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1004-分组添加(api.goods_groups.store)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam name required 分组名子 Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param StoreRequest $request
     * @param Result $result
     * @return array
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $this->goodsGroupHandler->store($request);
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1004-分组修改(api.goods_groups.update)
     * @queryParam shop_id required 店铺ID Example: 1
     * @queryParam name required 分组名子 Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, Result $result)
    {
        try {
            $id = $request->get('id');
            $this->goodsGroupHandler->update($request, $id);
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1004-分组删除(api.goods_groups.delete)
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
            $this->goodsGroupHandler->delete($request->get('id'));
            $result->setMessage('删除成功');
            $result->succeed(true);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
