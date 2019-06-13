<?php

namespace App\Http\Controllers\Api;

use App\Handlers\PageContentsItemHandler;
use App\Http\Requests\Api\PageContentItem\UpdateRequest;
use App\Http\Requests\Api\PageContentItem\DeleteRequest;
use App\Http\Requests\Api\PageContentItem\ListRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;
use App\Models\PageContentsItem;

class PageContentsItemController extends Controller
{
    private $pageContentsItemHandler;

    public function __construct(PageContentsItemHandler $pageContentsItemHandler)
    {
        $this->pageContentsItemHandler = $pageContentsItemHandler;
    }
    /**
     * 内容图片列表查询
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->pageContentsItemHandler->page($request);
        if ($data) {
            $data = $data->toArray();
        }
        $result->succeed($data);
        return response()->json($result->toArray());
    }

    /**
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, Result $result)
    {
        try {
            $data = $this->pageContentsItemHandler->update($request);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }


        return $result->toArray();
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
            $goodsGroup = PageContentsItem::find($id);
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
