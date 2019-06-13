<?php

namespace App\Http\Controllers\Admin;

use App\Models\Goods;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['brand_id', 'category_id', 'content', 'deduct_stock_type', 'delivery_id', 'goods_sort', 'introduction', 'name', 'publish_status', 'sales_actual', 'sales_initial', 'sales_status', 'shop_id', 'spec_type', 'title', 'version'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["brand_id" => "品牌ID", "category_id" => "类目ID", "content" => "商品详情", "created_at" => "创建时间", "deduct_stock_type" => "扣减库存的方式", "delivery_id" => "运费模版", "goods_sort" => "商品排序", "id" => "ID", "introduction" => "商品简介", "name" => "商品名称", "publish_status" => "发布状态 - 0:下架,1:上架", "sales_actual" => "实际销售", "sales_initial" => "初始销量", "sales_status" => "商品状态 - SOLD_OUT:售罄,ON_SALE:在售, PRE_SALE:预售", "shop_id" => "店铺ID", "spec_type" => "规格类型", "title" => "商品标题", "updated_at" => "更新时间", "version" => "版本号"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Goods $goods
     * @return mixed
     */
    public function index(Request $request, Goods $goods)
    {
        return $this->backend_view('goods.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Goods::query();

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
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Goods $goods
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Goods $goods)
    {

        return $this->backend_view('goods.create_edit', compact('goods'));
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
            $model = Goods::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Goods $goods
     * @return mixed
     */
    public function edit(Goods $goods)
    {

        return $this->backend_view('goods.create_edit', compact('goods'));
    }

    /**
     * 详情
     *
     * @param Goods $goods
     * @return mixed
     */
    public function detail(Goods $goods)
    {
        $goods->load('main_images', 'logo_image', 'detail_images', 'specs');
        return $this->backend_view('goods.detail', compact('goods'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Goods $goods
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Goods $goods, Result $result)
    {
        try {
            $goods->update($request->only($this->fields));
            $result->succeed($goods);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Goods $goods
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Goods $goods, Result $result)
    {
        if (!$goods) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goods->delete();
            if ($del) {
                $result->succeed($goods);
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
            $dels = Goods::whereIn('id', $ids)->delete();
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

//## 路由：Goods
//$router->get('goods', 'GoodsController@index')->name('admin.goods');
//$router->get('goods/create', 'GoodsController@create')->name('admin.goods.create');
//$router->get('goods/list', 'GoodsController@list')->name('admin.goods.list');
//$router->post('goods/store', 'GoodsController@store')->name('admin.goods.store');
//$router->get('goods/edit/{goods}', 'GoodsController@edit')->name('admin.goods.edit');//隐式绑定
//$router->post('goods/update/{goods}', 'GoodsController@update')->name('admin.goods.update');//隐式绑定
//$router->get('goods/destroy/{goods}', 'GoodsController@destroy')->name('admin.goods.destroy');//隐式绑定
//$router->post('goods/destroyBat', 'GoodsController@destroyBat')->name('admin.goods.destroyBat');

}
