<?php


namespace App\Handlers;

use Illuminate\Http\Request;
use App\Models\PageContentsItem;
use Exception;

class PageContentsItemHandler
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request,$flag = 'app')
    {
        $query = PageContentsItem::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['page_contents_id'])) {
                    if (!empty($value)) {
                        $query->where($field, $value);
                    }
                }
            }
        }
        if ($flag == 'wx')
            $query->where('is_show',PageContentsItem::SHOW_STATUS_ON_SHOW);

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('sort', 'asc');
        $query->select(['id', 'image_url', 'is_show', 'sort']);
        $data = $query->paginate($per_page);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function store($data)
    {
        $row = [];

        foreach (PageContentsItem::$fields as $field) {
            if ($data[$field] === '' || $data[$field] === null)
                throw new Exception($field . "不能为空");
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return PageContentsItem::create($row);
    }

    /**
     * 图片激活隐藏
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function update(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('is_show');
        $page_content = PageContentsItem::find($id);
        if (!$page_content) {
            throw new Exception('内容图片不存在');
        }
        if ($status == 'display')
            $page_content->is_show = PageContentsItem::SHOW_STATUS_ON_SHOW;
        elseif ($status == 'hidden')
            $page_content->is_show = PageContentsItem::SHOW_STATUS_SHOW_OUT;

        $page_content->save();
        return $page_content;
    }

    public function picMove($data)
    {
        $page_content_item = PageContentsItem::find($data['id']);
        if (!$page_content_item) {
            throw new Exception('内容图片不存在');
        }
        $page_content_item->sort = $data['sort'];
        return $page_content_item->save();
    }
}