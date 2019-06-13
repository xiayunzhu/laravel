<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrgGoodsSpec;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrgGoodsSpecsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['org_goods_id','org_goods_no','org_goods_price','line_price','virtual_quantity','quantity_offset','virtual_sold_num','sold_num','barcode','weight','spec_name','publish_status','spec_code','image_url'];

    public static $fieldsCollect = ['id'=>'','org_goods_id'=>'','org_goods_no'=>'','org_goods_price'=>'','line_price'=>'','virtual_quantity'=>'','quantity_offset'=>'','virtual_sold_num'=>'','sold_num'=>'','barcode'=>'','weight'=>'','spec_name'=>'','publish_status'=>'','spec_code'=>''];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","org_goods_id"=>"org商品ID","org_goods_no"=>"商品编号","org_goods_price"=>"商品价格","line_price"=>"商品划线价格","virtual_quantity"=>"虚拟库存","quantity_offset"=>"库存偏移量","virtual_sold_num"=>"虚拟销量","sold_num"=>"销售数量","barcode"=>"商品条码","weight"=>"商品重量KG","spec_name"=>"规格名称","publish_status"=>"发布状态 - 0:下架,1:上架","spec_code"=>"ERP规格编码",'image_url'=>'图片地址',"created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrgGoodsSpec $orgGoodsSpec
     * @return mixed
     */
    public function index(Request $request, OrgGoodsSpec $orgGoodsSpec)
    {
        return $this->backend_view('orgGoodsSpecs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrgGoodsSpec::query();

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
     * @param OrgGoodsSpec $orgGoodsSpec
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrgGoodsSpec $orgGoodsSpec)
    {

        return $this->backend_view('orgGoodsSpecs.create_edit', compact('orgGoodsSpec'));
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
            $model = OrgGoodsSpec::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrgGoodsSpec $orgGoodsSpec
     * @return mixed
     */
    public function edit(OrgGoodsSpec $orgGoodsSpec)
    {

        return $this->backend_view('orgGoodsSpecs.create_edit', compact('orgGoodsSpec'));
    }

    /**
     * 更新
     * @param Request $request
     * @param OrgGoodsSpec $orgGoodsSpec
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrgGoodsSpec $orgGoodsSpec, Result $result)
    {
        try {
            $orgGoodsSpec->update($request->only($this->fields));
            $result->succeed($orgGoodsSpec);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrgGoodsSpec $orgGoodsSpec
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrgGoodsSpec $orgGoodsSpec, Result $result)
    {
        if (!$orgGoodsSpec) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orgGoodsSpec->delete();
            if ($del) {
                $result->succeed($orgGoodsSpec);
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
            $dels = OrgGoodsSpec::whereIn('id', $ids)->delete();
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

//## 路由：OrgGoodsSpec
//$router->get('orgGoodsSpecs', 'OrgGoodsSpecsController@index')->name('admin.orgGoodsSpecs');
//$router->get('orgGoodsSpecs/create', 'OrgGoodsSpecsController@create')->name('admin.orgGoodsSpecs.create');
//$router->get('orgGoodsSpecs/list', 'OrgGoodsSpecsController@list')->name('admin.orgGoodsSpecs.list');
//$router->post('orgGoodsSpecs/store', 'OrgGoodsSpecsController@store')->name('admin.orgGoodsSpecs.store');
//$router->get('orgGoodsSpecs/edit/{orgGoodsSpec}', 'OrgGoodsSpecsController@edit')->name('admin.orgGoodsSpecs.edit');//隐式绑定
//$router->post('orgGoodsSpecs/update/{orgGoodsSpec}', 'OrgGoodsSpecsController@update')->name('admin.orgGoodsSpecs.update');//隐式绑定
//$router->get('orgGoodsSpecs/destroy/{orgGoodsSpec}', 'OrgGoodsSpecsController@destroy')->name('admin.orgGoodsSpecs.destroy');//隐式绑定
//$router->post('orgGoodsSpecs/destroyBat', 'OrgGoodsSpecsController@destroyBat')->name('admin.orgGoodsSpecs.destroyBat');

}
