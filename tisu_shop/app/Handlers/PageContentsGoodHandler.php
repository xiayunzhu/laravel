<?php


namespace App\Handlers;

use App\Http\Requests\Api\PageContentGood\ListRequest;
use App\Models\PageContentsGood;
use Exception;
use Illuminate\Http\Request;

class PageContentsGoodHandler
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = PageContentsGood::query();

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

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $query->select();
        $data = $query->paginate($per_page);
        $data->load(['good']);

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

        foreach (PageContentsGood::$fields as $field) {
            if ($data[$field] === '' || $data[$field] === null)
                throw new Exception($field."不能为空");
            if (isset($data[$field]))
                $row[$field] = $data[$field];
        }

        return PageContentsGood::create($row);
    }

}