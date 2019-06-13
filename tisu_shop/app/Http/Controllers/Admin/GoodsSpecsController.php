<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsSpec;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsSpecsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['goods_id','goods_no','goods_price','line_price','quantity','quantity_offset','sales_num','barcode','weight','shop_id','publish_status','spec_code'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","goods_id"=>"古德萨德","goods_no"=>"古德斯诺","goods_price"=>"古斯塔格价格","line_price"=>"线性价格","quantity"=>"量","quantity_offset"=>"定量偏移","sales_num"=>"售货员","barcode"=>"条形码","weight"=>"重量","shop_id"=>"购物狂","publish_status"=>"出版地位","spec_code"=>"规格代码","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param GoodsSpec $goodsSpec
     * @return mixed
     */
    public function index(Request $request, GoodsSpec $goodsSpec)
    {
        return $this->backend_view('goodsSpecs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = GoodsSpec::query();

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
     * @param GoodsSpec $goodsSpec
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(GoodsSpec $goodsSpec)
    {

        return $this->backend_view('goodsSpecs.create_edit', compact('goodsSpec'));
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
            $model = GoodsSpec::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param GoodsSpec $goodsSpec
     * @return mixed
     */
    public function edit(GoodsSpec $goodsSpec)
    {

        return $this->backend_view('goodsSpecs.create_edit', compact('goodsSpec'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param GoodsSpec $goodsSpec
     * @param Result $result
     * @return array
     */
    public function update(Request $request, GoodsSpec $goodsSpec, Result $result)
    {
        try {
            $goodsSpec->update($request->only($this->fields));
            $result->succeed($goodsSpec);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param GoodsSpec $goodsSpec
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(GoodsSpec $goodsSpec, Result $result)
    {
        if (!$goodsSpec) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goodsSpec->delete();
            if ($del) {
                $result->succeed($goodsSpec);
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
            $dels = GoodsSpec::whereIn('id', $ids)->delete();
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

//## 路由：GoodsSpec
//$router->get('goodsSpecs', 'GoodsSpecsController@index')->name('admin.goodsSpecs');
//$router->get('goodsSpecs/create', 'GoodsSpecsController@create')->name('admin.goodsSpecs.create');
//$router->get('goodsSpecs/list', 'GoodsSpecsController@list')->name('admin.goodsSpecs.list');
//$router->post('goodsSpecs/store', 'GoodsSpecsController@store')->name('admin.goodsSpecs.store');
//$router->get('goodsSpecs/edit/{goodsSpec}', 'GoodsSpecsController@edit')->name('admin.goodsSpecs.edit');//隐式绑定
//$router->post('goodsSpecs/update/{goodsSpec}', 'GoodsSpecsController@update')->name('admin.goodsSpecs.update');//隐式绑定
//$router->get('goodsSpecs/destroy/{goodsSpec}', 'GoodsSpecsController@destroy')->name('admin.goodsSpecs.destroy');//隐式绑定
//$router->post('goodsSpecs/destroyBat', 'GoodsSpecsController@destroyBat')->name('admin.goodsSpecs.destroyBat');

}
