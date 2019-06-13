<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\RegionsHandler;
use App\Models\Region;
use Illuminate\Http\Request;
use Ml\Response\Result;

class RegionsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['pid','shortname','name','merger_name','level','pinyin','code','zip_code','first','lng','lat'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","pid"=>"上级ID","shortname"=>"简称","name"=>"名称","merger_name"=>"名称","level"=>"级别","pinyin"=>"拼音","code"=>"区域编码","zip_code"=>"邮政编码","first"=>"首字母","lng"=>"经度","lat"=>"纬度","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Region $region
     * @return mixed
     */
    public function index(Request $request, Region $region)
    {
        return $this->backend_view('regions.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Region::query();

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
     * @param Region $region
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Region $region)
    {

        return $this->backend_view('regions.create_edit', compact('region'));
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
            $model = Region::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Region $region
     * @return mixed
     */
    public function edit(Region $region)
    {

        return $this->backend_view('regions.create_edit', compact('region'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Region $region
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Region $region, Result $result)
    {
        try {
            $region->update($request->only($this->fields));
            $result->succeed($region);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Region $region
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Region $region, Result $result)
    {
        if (!$region) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $region->delete();
            if ($del) {
                $result->succeed($region);
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
            $dels = Region::whereIn('id', $ids)->delete();
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
    /*
     * 地址列表
     * */
    public function regions_list(){
        //        Region::where('')
    }


    /**
     * 选择地区的窗口页面
     * @param Region $region
     * @param Request $request
     * @param RegionsHandler $regionsHandler
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addressWindow(Region $region,Request $request,RegionsHandler $regionsHandler)
    {
        $address = $regionsHandler->list($request);
        foreach ($address as $key => $value){
            $address[$key]['citys'] = $regionsHandler->cities($value['id']);
        }

        return $this->backend_view('regions.address_window', compact('region','address'));
    }





//## 路由：Region
//$router->get('regions', 'RegionsController@index')->name('admin.regions');
//$router->get('regions/create', 'RegionsController@create')->name('admin.regions.create');
//$router->get('regions/list', 'RegionsController@list')->name('admin.regions.list');
//$router->post('regions/store', 'RegionsController@store')->name('admin.regions.store');
//$router->get('regions/edit/{region}', 'RegionsController@edit')->name('admin.regions.edit');//隐式绑定
//$router->post('regions/update/{region}', 'RegionsController@update')->name('admin.regions.update');//隐式绑定
//$router->get('regions/destroy/{region}', 'RegionsController@destroy')->name('admin.regions.destroy');//隐式绑定
//$router->post('regions/destroyBat', 'RegionsController@destroyBat')->name('admin.regions.destroyBat');

}
