<?php

namespace App\Http\Controllers\Api;

use App\Handlers\PageContentHandler;
use App\Http\Requests\Api\PageContent\StoreRequest;
use App\Http\Requests\Api\PageContent\UpdateRequest;
use App\Http\Requests\Api\PageContent\ListRequest;
use App\Http\Requests\Api\PageContent\DeleteRequest;
use App\Lib\Response\Result;
use App\Http\Controllers\Controller;
use App\Models\PageContent;

class PageContentController extends Controller
{
    private $pageContentHandler;

    public function __construct(PageContentHandler $pageContentHandler)
    {
        $this->pageContentHandler = $pageContentHandler;
    }
    /**
     * 卡片列表查询
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->pageContentHandler->page($request);
        if ($data) {
            $data = $data->toArray();
        }
        $result->succeed($data);
        return response()->json($result->toArray());
    }

    /**
     * @param StoreRequest $request
     * @param Result $result
     * @return mixed
     */
    public function store(StoreRequest $request, Result $result)
    {
        try {
            $data = $this->pageContentHandler->store($request);
            if ($data) {
                $data = $data->toArray();

            }
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage(), $exception->getCode());
        }

        return $result->toArray();
    }
    /**
     * @param UpdateRequest $request
     * @param Result $result
     * @return array
     */
    public function update(UpdateRequest $request, Result $result)
    {
        try {
            $data = $this->pageContentHandler->update($request);
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
            $goodsGroup = PageContent::find($id);
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
