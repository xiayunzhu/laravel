<?php

namespace App\Http\Controllers\WeChat;

use App\Exceptions\GoodsException;
use App\Handlers\GoodsHandler;
use App\Http\Requests\WeChat\Goods\DetailRequest;
use App\Http\Requests\WeChat\Goods\ListRequest;
use App\Lib\Response\Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //
    /**
     * @var GoodsHandler
     */
    private $goodsHandler;

    //
    public function __construct(GoodsHandler $goodsHandler)
    {
        $this->goodsHandler = $goodsHandler;

    }

    /**
     * 商品列表查询
     *
     * @param ListRequest $request
     * @param Result $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->goodsHandler->page($request);
        if ($data) {
            $data->load(['specs', 'logo_image']);
            $data = $data->toArray();
            $data = fmt_array($data, ['file_url' => 'image_link', 'image_url' => 'image_link']);
        }
        $result->succeed($data);

        return response()->json($result->toArray());
    }

    /**
     *
     * @param DetailRequest $request
     * @param Result $result
     * @return array
     */
    public function detail(DetailRequest $request, Result $result)
    {
        try {
            $data = $this->goodsHandler->detail($request);
            if ($data) {
                $data->load(['specs', 'main_images', 'detail_images', 'has_specs', 'has_specs.spec', 'has_specs.specValue']);
                $data = $data->toArray();
                $data = fmt_array($data, ['file_url' => 'image_link', 'image_url' => 'image_link']);
            }

            $result->succeed($data);
        } catch (\Exception $exception) {
            if ($exception instanceof GoodsException)
                $result->failed($exception->getMessage(), $exception->getCode());
            else
                $result->failed('服务器异常,请稍后尝试');
        }
        return $result->toArray();
    }
}
