<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsHasSpec;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsHasSpecsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['goods_id','spec_id','spec_value_id','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","goods_id"=>"商品ID","spec_id"=>"规格属性ID","spec_value_id"=>"规格属性值ID","shop_id"=>"店铺ID","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param GoodsHasSpec $goodsHasSpec
     * @return mixed
     */
    public function index(Request $request, GoodsHasSpec $goodsHasSpec)
    {
        return $this->backend_view('goodsHasSpecs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = GoodsHasSpec::query();

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
     * @param GoodsHasSpec $goodsHasSpec
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(GoodsHasSpec $goodsHasSpec)
    {

        return $this->backend_view('goodsHasSpecs.create_edit', compact('goodsHasSpec'));
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
            $model = GoodsHasSpec::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param GoodsHasSpec $goodsHasSpec
     * @return mixed
     */
    public function edit(GoodsHasSpec $goodsHasSpec)
    {

        return $this->backend_view('goodsHasSpecs.create_edit', compact('goodsHasSpec'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param GoodsHasSpec $goodsHasSpec
     * @param Result $result
     * @return array
     */
    public function update(Request $request, GoodsHasSpec $goodsHasSpec, Result $result)
    {
        try {
            $goodsHasSpec->update($request->only($this->fields));
            $result->succeed($goodsHasSpec);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param GoodsHasSpec $goodsHasSpec
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(GoodsHasSpec $goodsHasSpec, Result $result)
    {
        if (!$goodsHasSpec) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goodsHasSpec->delete();
            if ($del) {
                $result->succeed($goodsHasSpec);
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
            $dels = GoodsHasSpec::whereIn('id', $ids)->delete();
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

//## 路由：GoodsHasSpec
//$router->get('goodsHasSpecs', 'GoodsHasSpecsController@index')->name('admin.goodsHasSpecs');
//$router->get('goodsHasSpecs/create', 'GoodsHasSpecsController@create')->name('admin.goodsHasSpecs.create');
//$router->get('goodsHasSpecs/list', 'GoodsHasSpecsController@list')->name('admin.goodsHasSpecs.list');
//$router->post('goodsHasSpecs/store', 'GoodsHasSpecsController@store')->name('admin.goodsHasSpecs.store');
//$router->get('goodsHasSpecs/edit/{goodsHasSpec}', 'GoodsHasSpecsController@edit')->name('admin.goodsHasSpecs.edit');//隐式绑定
//$router->post('goodsHasSpecs/update/{goodsHasSpec}', 'GoodsHasSpecsController@update')->name('admin.goodsHasSpecs.update');//隐式绑定
//$router->get('goodsHasSpecs/destroy/{goodsHasSpec}', 'GoodsHasSpecsController@destroy')->name('admin.goodsHasSpecs.destroy');//隐式绑定
//$router->post('goodsHasSpecs/destroyBat', 'GoodsHasSpecsController@destroyBat')->name('admin.goodsHasSpecs.destroyBat');

}
