<?php

namespace App\Http\Controllers\Admin;

use App\Models\Favorites;
use Illuminate\Http\Request;
use Ml\Response\Result;

class FavoritesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['goods_id', 'buyer_id', 'shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "goods_id" => "古德萨德", "buyer_id" => "布埃尔齐德", "shop_id" => "购物狂", "deleted_at" => "删除时间", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Favorites $favorites
     * @return mixed
     */
    public function index(Request $request, Favorites $favorites)
    {
        return $this->backend_view('favorites.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Favorites::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->load(['goods', 'shop', 'user']);
        //$data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Favorites $favorites
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Favorites $favorites)
    {

        return $this->backend_view('favorites.create_edit', compact('favorites'));
    }

    /**
     * 添加
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function store(Request $request, Result $result)
    {
        try {
            $model = Favorites::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Favorites $favorites
     * @return mixed
     */
    public function edit(Favorites $favorites)
    {

        return $this->backend_view('favorites.create_edit', compact('favorites'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Favorites $favorites
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Favorites $favorites, Result $result)
    {
        try {
            $favorites->update($request->only($this->fields));
            $result->succeed($favorites);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Favorites $favorites
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Favorites $favorites, Result $result)
    {
        if (!$favorites) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $favorites->delete();
            if ($del) {
                $result->succeed($favorites);
            } else {
                $result->failed('删除失败');
            }
        }

        return $result->toArray();
    }


    /**
     * 批量删除
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function destroyBat(Request $request, Result $result)
    {
        $ids = $request->get('ids');
        if ($ids && is_array($ids)) {
            $dels = Favorites::whereIn('id', $ids)->delete();
            if ($dels > 0) {
                $result->succeed();
            } else {
                $result->failed('删除失败');
            }
        } else {
            $result->failed('参数错误');
        }

        return $result->toArray();
    }



}
