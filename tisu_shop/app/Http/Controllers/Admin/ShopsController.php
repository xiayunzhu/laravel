<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ShopException;
use App\Handlers\ShopManageHandler;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Ml\Response\Result;

class ShopsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['shop_code','shop_nick','shop_name','icon_url','introduction','user_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","shop_code"=>"店铺代号","shop_nick"=>"店铺昵称","shop_name"=>"店铺名称","icon_url"=>"店铺图标","introduction"=>"店铺简介","user_id"=>"归属的用户ID","created_at"=>"创建时间","updated_at"=>"更新时间"];

    private $shopManageHandler;
    public function __construct(ShopManageHandler $shopManageHandler)
    {
        $this->shopManageHandler = $shopManageHandler;
    }

    /**
     * 列表
     *
     * @param Request $request
     * @param Shop $shop
     * @return mixed
     */
    public function index(Request $request, Shop $shop)
    {
        return $this->backend_view('shops.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Shop::query();
        $queryFields = $request->get('queryFields');

        if ( $queryFields['seller']){
            if ($tmp = User::where('name',$queryFields['seller'])->get()){
                $user_id = @$tmp[0]->id;
                unset($queryFields['seller']);
                $queryFields['user_id'] = $user_id;
            }

        }


        //查询条件处理
        if ($queryFields) {
            foreach ($queryFields as $field => $value) {
               if(!empty($value)){
                   if (strpos($field, 'name') !== false ||strpos($field, 'nick') !== false) {
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
        $data = $query->with(['user'])->paginate($per_page);
        if ($data) {
            $data = $data->toArray();

            $fmt_arr = [ 'icon_url' => 'image_link'];
            $data = fmt_array($data, $fmt_arr);
        }
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Shop $shop
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Shop $shop)
    {


        return $this->backend_view('shops.create_edit', compact('shop'));
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
            \DB::beginTransaction();

            $model = Shop::create($request->only($this->fields));
            if ($model){
                $res = $this->shopManageHandler->create($model->id,$request->get('user_id'));
                if (!$res){
                    \DB::rollback();
                    throw new ShopException("商品标签添加失败");
                }
            }
            \DB::commit();
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Shop $shop
     * @return mixed
     */
    public function edit(Shop $shop)
    {

        $shop = Shop::where('id',$shop['id'])->with(['user'])->get()[0];
        $shop->username = $shop->user->name;
        return $this->backend_view('shops.create_edit', compact('shop'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Shop $shop
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Shop $shop, Result $result)
    {
        try {

            $shop->update($request->only($this->fields));
            $result->succeed($shop);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Shop $shop
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Shop $shop, Result $result)
    {
        if (!$shop) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $shop->delete();
            if ($del) {
                $result->succeed($shop);
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
            $dels = Shop::whereIn('id', $ids)->delete();
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

//## 路由：Shop
//$router->get('shops', 'ShopsController@index')->name('admin.shops');
//$router->get('shops/create', 'ShopsController@create')->name('admin.shops.create');
//$router->get('shops/list', 'ShopsController@list')->name('admin.shops.list');
//$router->post('shops/store', 'ShopsController@store')->name('admin.shops.store');
//$router->get('shops/edit/{shop}', 'ShopsController@edit')->name('admin.shops.edit');//隐式绑定
//$router->post('shops/update/{shop}', 'ShopsController@update')->name('admin.shops.update');//隐式绑定
//$router->get('shops/destroy/{shop}', 'ShopsController@destroy')->name('admin.shops.destroy');//隐式绑定
//$router->post('shops/destroyBat', 'ShopsController@destroyBat')->name('admin.shops.destroyBat');

}
