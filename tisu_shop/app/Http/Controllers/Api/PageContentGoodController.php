<?php

namespace App\Http\Controllers\Api;

use App\Handlers\PageContentsGoodHandler;
use App\Http\Requests\Api\PageContentGood\DeleteRequest;
use App\Http\Requests\Api\PageContentGood\ListRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;
use App\Models\PageContentsGood;

class PageContentGoodController extends Controller
{
    private $pageContentsGoodHandler;

    public function __construct(PageContentsGoodHandler $pageContentsGoodHandler)
    {
        $this->pageContentsGoodHandler = $pageContentsGoodHandler;
    }
    /**
     * 商品列表查询
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */

    public function list(ListRequest $request, Result $result)
    {
        $data = $this->pageContentsGoodHandler->page($request);
        if ($data) {
            $data = $data->toArray();
        }
        $result->succeed($data);
        return response()->json($result->toArray());
    }


        /**
     * @param DeleteRequest $request
     * @param Result $result
     * @return array
     */
    public function delete(DeleteRequest $request, Result $result)
    {
        $id = $request->get('id');
        try {
            $goodsGroup = PageContentsGood::find($id);
            if ($goodsGroup) {
                $goodsGroup->delete();
                $result->setMessage('删除成功');
                $result->succeed($goodsGroup);
            } else {
                $result->failed('已删除');
            }

        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }
        return $result->toArray();
    }
}
