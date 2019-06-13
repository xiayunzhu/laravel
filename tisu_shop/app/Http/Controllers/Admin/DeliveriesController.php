<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deliveries;
use App\Models\DeliveryRule;
use App\Handlers\RegionsHandler;
use Illuminate\Http\Request;
use Ml\Response\Result;

class DeliveriesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['name','method','sort','rules'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","name"=>"模版名称","method"=>"计费方式","sort"=>"排序","created_at"=>"创建时间","updated_at"=>"更新时间",'rule'=>'运费模板规则'];

    /**
     * 列表
     *
     * @param Request $request
     * @param Deliveries $deliveries
     * @return mixed
     */
    public function index(Request $request, Deliveries $deliveries)
    {
        return $this->backend_view('deliveries.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Deliveries::query();

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
     * @param Deliveries $deliveries
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Deliveries $deliveries)
    {

        return $this->backend_view('deliveries.create_edit', compact('deliveries'));
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
            $deliveriesData = $request->only($this->fields);
            $deliveriesRulesData = $deliveriesData['rules'];
            unset($deliveriesData['rules']);

            $deliveriesModel = Deliveries::create($deliveriesData);
            foreach ($deliveriesRulesData as $value){
                unset($value['id']);
                $value['delivery_id'] = $deliveriesModel->id;
                DeliveryRule::create($value);
            }
            \DB::commit();
            $result->succeed($deliveriesModel);
        } catch (\Exception $exception) {
            \DB::rollback();
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Deliveries $deliveries
     * @param RegionsHandler $regionsHandler
     * @return mixed
     */
    public function edit(Deliveries $deliveries,RegionsHandler $regionsHandler)
    {
        $deliveries->load('rules');
        foreach ($deliveries['rules'] as $key => $value) {
            $regionsElement = '';
            $tmp = explode(",", $value['region']);
            foreach ($tmp as $k => $v) {
                $node = $regionsHandler->region($v)[0];
                if ($node->pid == 0) {
                    $regionsElement = rtrim($regionsElement,'、');
                    $regionsElement .= ')' . $node['name'] . '( ';
                } else {
                    $regionsElement .=  $node['name'] . '、';
                }
            }
            $deliveries['rules'][$key]['regionsElement'] =rtrim(substr($regionsElement,1),'、').')';
        }
        return $this->backend_view('deliveries.create_edit', compact('deliveries'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Deliveries $deliveries
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Deliveries $deliveries, Result $result)
    {
        \DB::beginTransaction();
        try {
            $deliveriesData = $request->only($this->fields);
            $deliveriesRulesData = $deliveriesData['rules'];
            unset($deliveriesData['rules']);
//            var_dump($deliveriesRulesData);die;

            DeliveryRule::where('delivery_id', $deliveries['id'])->delete();
            foreach ($deliveriesRulesData as $value){
                unset($value['id']);
                $value['delivery_id'] = $deliveries['id'];
                $deliveriesRulesModel = DeliveryRule::create($value);
            }
            $deliveries->update($deliveriesData);
            if ($deliveriesRulesModel)
                \DB::commit();
            $result->succeed($deliveries);
        } catch (\Exception $exception) {
            \DB::rollback();

            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Deliveries $deliveries
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Deliveries $deliveries, Result $result)
    {
        if (!$deliveries) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $deliveries->delete();
            if ($del) {
                $result->succeed($deliveries);
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
            $dels = Deliveries::whereIn('id', $ids)->delete();
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

//## 路由：Deliveries
//$router->get('deliveries', 'DeliveriesController@index')->name('admin.deliveries');
//$router->get('deliveries/create', 'DeliveriesController@create')->name('admin.deliveries.create');
//$router->get('deliveries/list', 'DeliveriesController@list')->name('admin.deliveries.list');
//$router->post('deliveries/store', 'DeliveriesController@store')->name('admin.deliveries.store');
//$router->get('deliveries/edit/{deliveries}', 'DeliveriesController@edit')->name('admin.deliveries.edit');//隐式绑定
//$router->post('deliveries/update/{deliveries}', 'DeliveriesController@update')->name('admin.deliveries.update');//隐式绑定
//$router->get('deliveries/destroy/{deliveries}', 'DeliveriesController@destroy')->name('admin.deliveries.destroy');//隐式绑定
//$router->post('deliveries/destroyBat', 'DeliveriesController@destroyBat')->name('admin.deliveries.destroyBat');

}
