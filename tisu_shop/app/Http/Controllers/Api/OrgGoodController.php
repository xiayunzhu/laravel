<?php

namespace App\Http\Controllers\Api;

use App\Handlers\OrgGoodHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrgGood\DetailRequest;
use App\Http\Requests\Api\OrgGood\ListRequest;
use App\Http\Requests\Api\OrgGood\SpecRequest;
use App\Lib\Response\Result;

/**
 * @group 平台仓库商品(platform warehouse goods)
 * author:zhaona
 * review_at:2019-05-11
 *
 * Class OrgGoodController
 * @package App\Http\Controllers\Api
 */
class OrgGoodController extends Controller
{
    private $orgGoodHandler;

    public function __construct(OrgGoodHandler $orgGoodHandler)
    {
        $this->orgGoodHandler = $orgGoodHandler;
    }

    /**
     * A1101-选款商品接口(api.orgGoods.list)
     * @queryParam category_id nullable 分类ID Example: 1
     * @queryParam sorting nullable 排序方式 Example: commissionDesc
     * @queryParam page nullable 页码 Example: 1
     * @queryParam per_page nullable 分页大小 Example: 10
     * @queryParam idsIn array 只查询集合内ID Example: [1,9]
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param ListRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->orgGoodHandler->page($request);

        $result->succeed($data);
        return response()->json($result->toArray());
    }

    /**
     * A1003-商品详情页面(api.orgGoods.detail)
     * @queryParam id required 原商品ID Example: 1
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->orgGoodHandler->detail($request);
            if ($data) {
                $data = fmt_array($data, ['file_url' => 'image_link', 'image_url' => 'image_link']);
            }
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }

    /**
     * A1003-原商品SKU详情选择信息 (api.orgGoods.spec)
     * @queryParam id required 原商品ID Example: 8
     * @queryParam __debugger 模拟登录账号. Example: 1
     *
     * @param SpecRequest $request
     * @param Result $result
     * @return array
     */
    public function spec(SpecRequest $request, Result $result)
    {
        try {
            $data = $this->orgGoodHandler->spec($request);
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
