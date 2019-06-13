<?php
/**
 * Created by PhpStorm.
 * User: ML-05
 * Date: 2019/3/30
 * Time: 11:12
 */
namespace App\Http\Controllers\WeChat;
use App\Handlers\PageContentHandler;
use App\Exceptions\PageContentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\PageContent\ListRequest;
use Ml\Response\Result;

class PageContentController extends Controller
{
    private $pageContentHandler;

    public function __construct(PageContentHandler $pageContentHandler)
    {
        $this->pageContentHandler = $pageContentHandler;
    }
    /**
     * 首页卡片列表
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
            $data = fmt_array($data, ['image_url' => 'image_link']);
        }
        $result->succeed($data);
        return $result->toArray();
    }

}