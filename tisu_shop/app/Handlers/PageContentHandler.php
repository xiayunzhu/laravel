<?php

namespace App\Handlers;


use App\Exceptions\PageContentException;
use App\Models\PageContent;
use App\Models\PageContentsItem;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;

class PageContentHandler
{

    private $pageContentsItemHandler;
    private $pageContentsGoodHandler;


    public function __construct(PageContentsItemHandler $pageContentsItemHandler,PageContentsGoodHandler $pageContentsGoodHandler){
        $this->pageContentsItemHandler = $pageContentsItemHandler;
        $this->pageContentsGoodHandler = $pageContentsGoodHandler;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = PageContent::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id'])) {
                    if (!empty($value)) {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $query->select(['id','title','describe','image_url','type','created_at']);
        $data = $query->paginate($per_page);

        return $data;
    }
    /**
     * @param Request $request
     * @return mixed
     * @throws PageContentException
     */
    public function store(Request $request)
    {
        $data = $request->only(['shop_id','image_url','title','describe','type']);
        $content_items = $request->get('content_items');
        $goods_ids = $request->get('goods_ids');

        \DB::beginTransaction();
        $row = [];
        foreach (PageContent::$fields as $field) {
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }
        $page_content = PageContent::create($row);

        ## 内容添加
        if (is_array($content_items)){
            foreach ($content_items as $content_item){
                $content_item['page_contents_id'] = $page_content->id;
                $content_item['is_show'] = PageContentsItem::SHOW_STATUS_ON_SHOW;
                $tmpRes = $this->pageContentsItemHandler->store($content_item);
                if (!$tmpRes){
                    \DB::rollback();
                    throw new PageContentException("内容添加失败");
                }
            }
        }

        ## 商品添加
        if (is_array($goods_ids)){
            foreach ($goods_ids as $goods_id){
                $goodData['page_contents_id'] = $page_content->id;
                $goodData['goods_id'] = $goods_id;
                $tmpRes = $this->pageContentsGoodHandler->store($goodData);
                if (!$tmpRes){
                    \DB::rollback();
                    throw new PageContentException("商品添加失败");
                }
            }
        }



        \DB::commit();
        return $page_content;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     * @throws PageContentException
     */
    public function update(Request $request)
    {
        $id = $request->get('id');
        $data = $request->only(['image_url', 'title', 'describe','type']);

        \DB::beginTransaction();

        $page_content = PageContent::find($id);
        if (!$page_content) {
            throw new PageContentException('卡片不存在');
        }
        foreach ($data as $field => $value) {
            $page_content->$field = $value;
        }

        $page_content_items = $request->get('content_items');
        foreach ($page_content_items as $page_content_item){
            $tmp = $this->pageContentsItemHandler->picMove($page_content_item);
            if (!$tmp){
                \DB::rollback();
                throw new PageContentException("图片移动失败");
            }
        }

        $page_content->save();
        \DB::commit();
        return $page_content;
    }

}