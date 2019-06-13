<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\GoodsException;
use App\Jobs\GoodsInfoChange;
use App\Models\OrgGood;
use App\Models\OrgGoodsLabels;
use App\Models\OrgGoodsSpec;
use App\Models\OrgGoodImage;
use App\Models\OrgGoodsHasSpec;
use App\Models\Specs;
use App\Models\SpecValues;
use App\Models\UploadFile;
use App\Models\Deliveries;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrgGoodsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['id', 'name', 'title', 'brand_id', 'category_id', 'spec_type', 'deduct_stock_type', 'content', 'introduction', 'sales_initial', 'sales_actual', 'goods_sort', 'delivery_id', 'sales_status', 'publish_status', 'version', 'specs', 'images', 'specs_values','label_values','commission_rate'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "name" => "商品名称", "title" => "商品标题", "brand_id" => "品牌ID", "category_id" => "类目ID", "spec_type" => "规格类型", "deduct_stock_type" => "扣减库存的方式", "content" => "商品详情", "introduction" => "商品简介", "sales_initial" => "初始销量", "sales_actual" => "实际销售", "goods_sort" => "商品排序", "delivery_id" => "运费模版", "sales_status" => "商品状态 - SOLD_OUT:售罄,ON_SALE:在售, PRE_SALE:预售", "publish_status" => "发布状态 - 0:下架,1:上架", "version" => "版本号", "created_at" => "创建时间", "updated_at" => "更新时间", 'specs' => '规格参数', 'images' => '商品图片', 'specs_values' => '商品详情参数','label_values'=>'商品标签','commission_rate'=>'佣金比例'];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrgGood $orgGood
     * @return mixed
     */
    public function index(Request $request, OrgGood $orgGood)
    {
        return $this->backend_view('orgGoods.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrgGood::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false || strpos($field, 'title') !== false) {
                        $query->where($field, 'like', '%' . $value . '%');
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }
        $query->with(['category', 'brand', 'deliveries']);

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
     * @param OrgGood $orgGood
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrgGood $orgGood)
    {

        $orgGood->spec = new OrgGoodsSpec();

        $deliveries = Deliveries::get();


        return $this->backend_view('orgGoods.create_edit', compact('orgGood', 'deliveries'));
    }

    /**
     * 添加
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function store(Request $request, Result $result)
    {
        \DB::beginTransaction();
        try {
            $dataOrgGood = $request->only($this->fields);

            $dataOrgGoodSpec = isset($dataOrgGood['specs']) ? $dataOrgGood['specs'] : [];   # org_goods_specs 商品规格表数据
            $dataOrgGoodImg = isset($dataOrgGood['images']) ? $dataOrgGood['images'] : [];  # 图片
            $dataGoodsHasSpecs = isset($dataOrgGood['specs_values']) ? $dataOrgGood['specs_values'] : []; # org_goods_has_specs详情参数表数据
            $dataLabelValues = isset($dataOrgGood['label_values']) ? $dataOrgGood['label_values'] : []; # org_goods_labels商品标签

            if (!count($dataOrgGoodSpec)){
                throw new GoodsException("商品规格必须填写，请最少输入一条商品规格");
            }

            unset($dataOrgGood['id']);
            unset($dataOrgGood['images']);
            unset($dataOrgGood['specs']);
            unset($dataOrgGood['specs_values']);
            unset($dataOrgGood['label_values']);
            $dataOrgGood['spec_type'] = count($dataOrgGoodSpec) > 1 ? OrgGood::SPEC_TYPE_MORE : OrgGood::SPEC_TYPE_ONE;

            $OrgGoodmodel = OrgGood::create($dataOrgGood);
            # 商品规格添加
            foreach ($dataOrgGoodSpec as &$value) {
                $value['org_goods_id'] = $OrgGoodmodel->id;

                if (empty($value['id'])) {
                    unset($value['id']);
                    $value['quantity_offset'] = $value['virtual_quantity'] - $value['actual_inventory'];
                    unset($value['actual_inventory']);
                }
                if (isset($value['id'])) {
                    unset($value['id']);
                }

                OrgGoodsSpec::create($value);
            }
            # 商品图片添加
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_LOGO]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_LOGO], $OrgGoodmodel->id, OrgGoodImage::PROPERTY_LOGO);
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_MAIN]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_MAIN], $OrgGoodmodel->id, OrgGoodImage::PROPERTY_MAIN);
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_DETAIL]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_DETAIL], $OrgGoodmodel->id, OrgGoodImage::PROPERTY_DETAIL);
            # 商品详情参数添加
            $tmpSpec['org_goods_id'] = $OrgGoodmodel->id;
            foreach ($dataGoodsHasSpecs as $key => $itemSpec) {
                $spec = SpecValues::where('id', $itemSpec)->with(['spec'])->first();
                $tmpSpec['spec_id'] = $spec['spec']['id'];
                $tmpSpec['spec_value_id'] = $itemSpec;
                $res0 = OrgGoodsHasSpec::create($tmpSpec);
            }

            # 商品标签添加
            $tmpLable['org_goods_id'] = $OrgGoodmodel->id;
            foreach ($dataLabelValues as $key => $value){
                $tmpLable['label_value'] = $value;
                if (empty($tmpLable['label_value']))
                    break;
                $res = OrgGoodsLabels::create($tmpLable);
                if (!$res){
                    \DB::rollback();
                    throw new GoodsException("商品标签添加失败");
                }
            }
            \DB::commit();
            $result->succeed($OrgGoodmodel);
        } catch (\Exception $exception) {
            \DB::rollBack();
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrgGood $orgGood
     * @return mixed
     */
    public function edit(OrgGood $orgGood)
    {
        $orgGood = OrgGood::where('id', $orgGood->id)->with(['detail_specs'])->first();
        $deliveries = Deliveries::get();
        $specs = Specs::get();
        foreach ($orgGood['detail_specs'] as $key => $value) {
            $orgGood['detail_specs'][$key]['all'] = Specs::where('id', $value['spec_id'])->with(['specValues'])->first();
        }

        if ($orgGood->spec_type == OrgGood::SPEC_TYPE_ONE) {
            $orgGood->load('spec');
        } else {
            $orgGood->load('specs');
        }
        $orgGood->load(['images','lables']);
        return $this->backend_view('orgGoods.create_edit', compact('orgGood', 'single', 'deliveries', 'specs'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OrgGood $orgGood
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrgGood $orgGood, Result $result)
    {
        \DB::beginTransaction();
        try {
            $dataOrgGood = $request->only($this->fields);

            $dataOrgGoodSpec = isset($dataOrgGood['specs']) ? $dataOrgGood['specs'] : [];   # org_goods_specs 商品规格表数据
            $dataOrgGoodImg = isset($dataOrgGood['images']) ? $dataOrgGood['images'] : [];  # 商品图片表数据
            $dataGoodsHasSpecs = isset($dataOrgGood['specs_values']) ? $dataOrgGood['specs_values'] : []; # org_goods_has_specs详情参数表数据
            $dataLabelValues = isset($dataOrgGood['label_values']) ? $dataOrgGood['label_values'] : []; # org_goods_labels商品标签

            if (!count($dataOrgGoodSpec)){
                throw new GoodsException("商品规格必须填写，请最少输入一条商品规格");
            }
            unset($dataOrgGood['images']);
            unset($dataOrgGood['specs']);
            unset($dataOrgGood['specs_values']);
            unset($dataOrgGood['label_values']);

            $dataOrgGood['spec_type'] = count($dataOrgGoodSpec) > 1 ? OrgGood::SPEC_TYPE_MORE : OrgGood::SPEC_TYPE_ONE;

            #商品图片修改
            OrgGoodImage::where('org_goods_id', $dataOrgGood['id'])->delete();
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_LOGO]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_LOGO], $dataOrgGood['id'], OrgGoodImage::PROPERTY_LOGO);
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_MAIN]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_MAIN], $dataOrgGood['id'], OrgGoodImage::PROPERTY_MAIN);
            if (isset($dataOrgGoodImg[OrgGoodImage::PROPERTY_DETAIL]))
                $this->doOrgGoodsImage($dataOrgGoodImg[OrgGoodImage::PROPERTY_DETAIL], $dataOrgGood['id'], OrgGoodImage::PROPERTY_DETAIL);

            # 商品规格修改
            foreach ($dataOrgGoodSpec as $key => $value) {
                if (isset($value['id'])) {
                    unset($value['quantity_offset']);
                    OrgGoodsSpec::where('id', $value['id'])->update($value);
                } else {
                    $value['quantity_offset'] = $value['virtual_quantity'] - $value['actual_inventory'];
                    unset($value['actual_inventory']);
                    unset($value['id']);
                    $value['org_goods_id'] = $orgGood->id;
                    OrgGoodsSpec::create($value);
                }

            }
            # 商品详情参数修改
            $tmpSpec['org_goods_id'] = $orgGood->id;
            OrgGoodsHasSpec::where('org_goods_id', $dataOrgGood['id'])->delete();
            foreach ($dataGoodsHasSpecs as $key => $itemSpec) {
                $spec = SpecValues::where('id', $itemSpec)->with(['spec'])->first();
                if (!$spec) {
                    throw new GoodsException("商品参数属性值不能为空");
                }
                $tmpSpec['spec_id'] = $spec['spec']['id'];
                $tmpSpec['spec_value_id'] = $itemSpec;

                OrgGoodsHasSpec::create($tmpSpec);
            }

            # 商品标签修改
            $tmpLable['org_goods_id'] = $orgGood->id;
            OrgGoodsLabels::where('org_goods_id', $dataOrgGood['id'])->delete();
            foreach ($dataLabelValues as $key => $value){
                $tmpLable['label_value'] = $value;
                if (empty($tmpLable['label_value']))
                    break;
                $res = OrgGoodsLabels::create($tmpLable);
                if (!$res){
                    \DB::rollback();
                    throw new GoodsException("商品标签修改失败");
                }
            }

            # 商品信息修改
            $res = $orgGood->update($dataOrgGood);
            if ($res) {
                \DB::commit();
                $result->succeed($orgGood);
            } else {
                \DB::rollback();
            }
        } catch (\Exception $exception) {
            \DB::rollback();
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrgGood $orgGood
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrgGood $orgGood, Result $result)
    {
        if (!$orgGood) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orgGood->delete();
            if ($del) {
                $result->succeed($orgGood);
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
            $dels = OrgGood::whereIn('id', $ids)->delete();
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

    /**
     * 商品图片处理
     * @param $imgArray 需要存储的图片数组
     * @param $orgGoodId 关联商品id
     * @param $param 属性:主图,列表图,详情图
     * @return string
     */
    public function doOrgGoodsImage($imgArray, $orgGoodId, $param)
    {

        $imgs = UploadFile::whereIn('id', $imgArray)->get();
        $dataImg['org_goods_id'] = $orgGoodId;
        foreach ($imgs as $key => $value) {
            $dataImg['image_id'] = $value['id'];
            $dataImg['file_url'] = $value['file_url'];
            $dataImg['property'] = $param;
            $dataImg['sort'] = $key;
            $dataImg['create_time'] = time();
            OrgGoodImage::create($dataImg);
        }
        return true;
    }

    /**
     * 商品所有可选参数规格查询
     * @param Request $request
     * @param Result $result
     * @param $spec_id
     * @return string
     */
    public function specs(Request $request, Result $result, $spec_id = null)
    {
        $spec_id = $request->get('spec_id');
        $specs = Specs::where('id', $spec_id)->with(['specValues'])->get()[0];
        $element = '<select name="specs_values[]"   required lay-verify="required" ><option value="">请选择</option>';
        foreach ($specs['specValues'] as $value) {
            $element .= '<option value="' . $value['id'] . '">' . $value['spec_value'] . '</option>';
        }
        return $result->succeed($element . "</select>");
    }

    /**
     * 商品版本升级
     * @param OrgGood $orgGood
     * @param Request $request
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function upVersion(OrgGood $orgGood, Request $request, Result $result)
    {

        $data['version'] = $request->get('version');
        dispatch((new GoodsInfoChange($orgGood))->onQueue('GoodsInfoChange'));

        try {
            $orgGood->update($data);
            $result->succeed($orgGood);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 选择图片的的窗口页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function erpWindow(Request $request)
    {
        return $this->backend_view('orgGoods.erp_window');
    }

//## 路由：OrgGood
//$router->get('orgGoods', 'OrgGoodsController@index')->name('admin.orgGoods');
//$router->get('orgGoods/create', 'OrgGoodsController@create')->name('admin.orgGoods.create');
//$router->get('orgGoods/list', 'OrgGoodsController@list')->name('admin.orgGoods.list');
//$router->post('orgGoods/store', 'OrgGoodsController@store')->name('admin.orgGoods.store');
//$router->get('orgGoods/edit/{orgGood}', 'OrgGoodsController@edit')->name('admin.orgGoods.edit');//隐式绑定
//$router->post('orgGoods/update/{orgGood}', 'OrgGoodsController@update')->name('admin.orgGoods.update');//隐式绑定
//$router->get('orgGoods/destroy/{orgGood}', 'OrgGoodsController@destroy')->name('admin.orgGoods.destroy');//隐式绑定
//$router->post('orgGoods/destroyBat', 'OrgGoodsController@destroyBat')->name('admin.orgGoods.destroyBat');

}
