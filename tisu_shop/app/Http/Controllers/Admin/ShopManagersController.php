<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopManager;
use Illuminate\Http\Request;
use Ml\Response\Result;

class ShopManagersController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['shop_id','user_id','type','status'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","shop_id"=>"SHOP_ID","user_id"=>"USER_ID","type"=>"TYPE","status"=>"STATUS","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param ShopManager $shopManager
     * @return mixed
     */
    public function index(Request $request, ShopManager $shopManager)
    {
        return $this->backend_view('shopManagers.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = ShopManager::query();

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
     * @param ShopManager $shopManager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ShopManager $shopManager)
    {

        return $this->backend_view('shopManagers.create_edit', compact('shopManager'));
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
            $model = ShopManager::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param ShopManager $shopManager
     * @return mixed
     */
    public function edit(ShopManager $shopManager)
    {

        return $this->backend_view('shopManagers.create_edit', compact('shopManager'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param ShopManager $shopManager
     * @param Result $result
     * @return array
     */
    public function update(Request $request, ShopManager $shopManager, Result $result)
    {
        try {
            $shopManager->update($request->only($this->fields));
            $result->succeed($shopManager);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param ShopManager $shopManager
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(ShopManager $shopManager, Result $result)
    {
        if (!$shopManager) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $shopManager->delete();
            if ($del) {
                $result->succeed($shopManager);
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
            $dels = ShopManager::whereIn('id', $ids)->delete();
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

//## 路由：ShopManager
//$router->get('shopManagers', 'ShopManagersController@index')->name('admin.shopManagers');
//$router->get('shopManagers/create', 'ShopManagersController@create')->name('admin.shopManagers.create');
//$router->get('shopManagers/list', 'ShopManagersController@list')->name('admin.shopManagers.list');
//$router->post('shopManagers/store', 'ShopManagersController@store')->name('admin.shopManagers.store');
//$router->get('shopManagers/edit/{shopManager}', 'ShopManagersController@edit')->name('admin.shopManagers.edit');//隐式绑定
//$router->post('shopManagers/update/{shopManager}', 'ShopManagersController@update')->name('admin.shopManagers.update');//隐式绑定
//$router->get('shopManagers/destroy/{shopManager}', 'ShopManagersController@destroy')->name('admin.shopManagers.destroy');//隐式绑定
//$router->post('shopManagers/destroyBat', 'ShopManagersController@destroyBat')->name('admin.shopManagers.destroyBat');

}
