<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 19:10
 */

namespace App\Handlers;


use App\Exceptions\GoodsGroupException;
use App\Models\GoodsGroup;
use Illuminate\Http\Request;

class GoodsGroupHandler
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = GoodsGroup::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $query->select(['id', 'name']);

        $data = $query->paginate($per_page);
        return $data;
    }

    /**
     * @param $id
     * @return mixed
     * @throws GoodsGroupException
     */
    public function detail($id)
    {
        $goodGroup = GoodsGroup::withCount('items')->find($id);
        if (!$goodGroup) {
            throw new GoodsGroupException('分组不存在或已删除');
        }
        return $goodGroup;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {

        $row = $request->only(GoodsGroup::$fields);

        return GoodsGroup::create($row);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws GoodsGroupException
     */
    public function update(Request $request, int $id)
    {
//        $id = $request->get('id');
        $goodsGroup = GoodsGroup::find($id);
        if ($goodsGroup) {
            $goodsGroup->name = $request->get('name');
            $goodsGroup->save();
        } else {
            throw new GoodsGroupException('分组不存在');
        }

        return $goodsGroup;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws GoodsGroupException
     */
    public function delete(int $id)
    {
        $goodsGroup = GoodsGroup::find($id);
        if ($goodsGroup) {
            $goodsGroup->delete();
        } else {
            throw new GoodsGroupException('已删除');
        }

        return $goodsGroup;
    }

}