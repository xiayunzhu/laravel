<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsGroupItem;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsGroupItemsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['goods_group_id','goods_id','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["created_at"=>"创建时间","goods_group_id"=>"分组ID","goods_id"=>"商品ID","id"=>"ID","shop_id"=>"店铺ID","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param GoodsGroupItem $goodsGroupItem
     * @return mixed
     */
    public function index(Request $request, GoodsGroupItem $goodsGroupItem)
    {
        return $this->backend_view('goodsGroupItems.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = GoodsGroupItem::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
               if(!empty($value)){
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
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param GoodsGroupItem $goodsGroupItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(GoodsGroupItem $goodsGroupItem)
    {

        return $this->backend_view('goodsGroupItems.create_edit', compact('goodsGroupItem'));
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
            $model = GoodsGroupItem::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param GoodsGroupItem $goodsGroupItem
     * @return mixed
     */
    public function edit(GoodsGroupItem $goodsGroupItem)
    {

        return $this->backend_view('goodsGroupItems.create_edit', compact('goodsGroupItem'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param GoodsGroupItem $goodsGroupItem
     * @param Result $result
     * @return array
     */
    public function update(Request $request, GoodsGroupItem $goodsGroupItem, Result $result)
    {
        try {
            $goodsGroupItem->update($request->only($this->fields));
            $result->succeed($goodsGroupItem);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param GoodsGroupItem $goodsGroupItem
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(GoodsGroupItem $goodsGroupItem, Result $result)
    {
        if (!$goodsGroupItem) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goodsGroupItem->delete();
            if ($del) {
                $result->succeed($goodsGroupItem);
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
            $dels = GoodsGroupItem::whereIn('id', $ids)->delete();
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

//## 路由：GoodsGroupItem
//$router->get('goodsGroupItems', 'GoodsGroupItemsController@index')->name('admin.goodsGroupItems');
//$router->get('goodsGroupItems/create', 'GoodsGroupItemsController@create')->name('admin.goodsGroupItems.create');
//$router->get('goodsGroupItems/list', 'GoodsGroupItemsController@list')->name('admin.goodsGroupItems.list');
//$router->post('goodsGroupItems/store', 'GoodsGroupItemsController@store')->name('admin.goodsGroupItems.store');
//$router->get('goodsGroupItems/edit/{goodsGroupItem}', 'GoodsGroupItemsController@edit')->name('admin.goodsGroupItems.edit');//隐式绑定
//$router->post('goodsGroupItems/update/{goodsGroupItem}', 'GoodsGroupItemsController@update')->name('admin.goodsGroupItems.update');//隐式绑定
//$router->get('goodsGroupItems/destroy/{goodsGroupItem}', 'GoodsGroupItemsController@destroy')->name('admin.goodsGroupItems.destroy');//隐式绑定
//$router->post('goodsGroupItems/destroyBat', 'GoodsGroupItemsController@destroyBat')->name('admin.goodsGroupItems.destroyBat');

}
