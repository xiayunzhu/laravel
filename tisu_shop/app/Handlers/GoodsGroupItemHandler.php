<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 19:10
 */

namespace App\Handlers;


use App\Exceptions\GoodsGroupItemException;
use App\Models\GoodsGroupItem;
use Illuminate\Http\Request;

class GoodsGroupItemHandler
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = GoodsGroupItem::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'goods_group_id'])) {
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

        $data = $query->paginate($per_page,['id','goods_group_id','goods_id']);
        return $data;
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws GoodsGroupItemException
     */
    public function store(Request $request)
    {

        $row = $request->only(GoodsGroupItem::$fields);
        $count = GoodsGroupItem::where($row)->count();
        if ($count) {
            throw new GoodsGroupItemException('已加入分组', 10006);
        }

        return GoodsGroupItem::create($row);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws GoodsGroupItemException
     */
    public function update(Request $request, int $id)
    {
//        $id = $request->get('id');
        $goodsGroup = GoodsGroupItem::find($id);
        if ($goodsGroup) {
            $goodsGroup->name = $request->get('name');
            $goodsGroup->save();
        } else {
            throw new GoodsGroupItemException('分组不存在');
        }

        return $goodsGroup;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws GoodsGroupItemException
     */
    public function delete(int $id)
    {
        $goodsGroup = GoodsGroupItem::find($id);
        if ($goodsGroup) {
            $goodsGroup->delete();
        } else {
            throw new GoodsGroupItemException('已删除');
        }

        return $goodsGroup;
    }

    /**
     * @param $ids
     * @return mixed
     * @throws GoodsGroupItemException
     */
    public function destroyBat($ids)
    {
        $dels = GoodsGroupItem::whereIn('id', $ids)->delete();
        if (!$dels) {
            throw new GoodsGroupItemException('删除失败');
        }

        return $dels;
    }

}