<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryRule;
use Illuminate\Http\Request;
use Ml\Response\Result;

class DeliveryRulesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['delivery_id','region','first','first_fee','additional','additional_fee'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","delivery_id"=>"运费模版ID","region"=>"可配送区域","first"=>"首重","first_fee"=>"首重费用","additional"=>"续重","additional_fee"=>"续重费用","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param DeliveryRule $deliveryRule
     * @return mixed
     */
    public function index(Request $request, DeliveryRule $deliveryRule)
    {
        return $this->backend_view('deliveryRules.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = DeliveryRule::query();

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
     * @param DeliveryRule $deliveryRule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(DeliveryRule $deliveryRule)
    {

        return $this->backend_view('deliveryRules.create_edit', compact('deliveryRule'));
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
            $model = DeliveryRule::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param DeliveryRule $deliveryRule
     * @return mixed
     */
    public function edit(DeliveryRule $deliveryRule)
    {

        return $this->backend_view('deliveryRules.create_edit', compact('deliveryRule'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param DeliveryRule $deliveryRule
     * @param Result $result
     * @return array
     */
    public function update(Request $request, DeliveryRule $deliveryRule, Result $result)
    {
        try {
            $deliveryRule->update($request->only($this->fields));
            $result->succeed($deliveryRule);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param DeliveryRule $deliveryRule
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(DeliveryRule $deliveryRule, Result $result)
    {
        if (!$deliveryRule) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $deliveryRule->delete();
            if ($del) {
                $result->succeed($deliveryRule);
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
            $dels = DeliveryRule::whereIn('id', $ids)->delete();
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

//## 路由：DeliveryRule
//$router->get('deliveryRules', 'DeliveryRulesController@index')->name('admin.deliveryRules');
//$router->get('deliveryRules/create', 'DeliveryRulesController@create')->name('admin.deliveryRules.create');
//$router->get('deliveryRules/list', 'DeliveryRulesController@list')->name('admin.deliveryRules.list');
//$router->post('deliveryRules/store', 'DeliveryRulesController@store')->name('admin.deliveryRules.store');
//$router->get('deliveryRules/edit/{deliveryRule}', 'DeliveryRulesController@edit')->name('admin.deliveryRules.edit');//隐式绑定
//$router->post('deliveryRules/update/{deliveryRule}', 'DeliveryRulesController@update')->name('admin.deliveryRules.update');//隐式绑定
//$router->get('deliveryRules/destroy/{deliveryRule}', 'DeliveryRulesController@destroy')->name('admin.deliveryRules.destroy');//隐式绑定
//$router->post('deliveryRules/destroyBat', 'DeliveryRulesController@destroyBat')->name('admin.deliveryRules.destroyBat');

}
