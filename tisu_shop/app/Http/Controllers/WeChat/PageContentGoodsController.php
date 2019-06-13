<?php
/**
 * Created by PhpStorm.
 * User: ML-05
 * Date: 2019/3/30
 * Time: 11:12
 */
namespace App\Http\Controllers\WeChat;
use App\Handlers\PageContentsGoodHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\WeChat\PageContentGood\ListRequest;

use Ml\Response\Result;

class PageContentGoodsController extends Controller
{
    private $pageContentsGoodHandler;

    public function __construct(PageContentsGoodHandler $pageContentsGoodHandler)
    {
        $this->pageContentsGoodHandler = $pageContentsGoodHandler;
    }
    /**
     * 首页卡片商品列表
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
            $data = fmt_array($data, ['image_url' => 'image_link']);
        }
        $result->succeed($data);
        return $result->toArray();
    }

}