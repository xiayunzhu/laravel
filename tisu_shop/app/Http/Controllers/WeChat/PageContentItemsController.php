<?php
/**
 * Created by PhpStorm.
 * User: ML-05
 * Date: 2019/3/30
 * Time: 11:12
 */
namespace App\Http\Controllers\WeChat;
use App\Handlers\PageContentsItemHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\PageContentItem\ListRequest;
use Ml\Response\Result;

class PageContentItemsController extends Controller
{
    private $pageContentsItemHandler;

    public function __construct(PageContentsItemHandler $pageContentsItemHandler)
    {
        $this->pageContentsItemHandler = $pageContentsItemHandler;
    }
    /**
     * 首页卡片内容图片列表
     *
     * @param ListRequest $request
     * @param Result $result
     * @return array
     */
    public function list(ListRequest $request, Result $result)
    {
        $data = $this->pageContentsItemHandler->page($request,$handle = 'wx');

        if ($data) {
            $data = $data->toArray();
            $data = fmt_array($data, ['file_url' => 'image_link']);
        }
        $result->succeed($data);
        return $result->toArray();
    }

}