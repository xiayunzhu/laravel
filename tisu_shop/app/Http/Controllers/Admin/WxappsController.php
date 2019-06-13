<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\Wxapp;
use Illuminate\Http\Request;
use Ml\Response\Result;

class WxappsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['app_name','app_id','app_secret','is_service','service_image_id','is_phone','phone_no','phone_image_id','mchid','apikey','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","app_name"=>"小程序名称","app_id"=>"小程序ID","app_secret"=>"小程序密钥","is_service"=>"是否服务","service_image_id"=>"是否服务","is_phone"=>"手机用户","phone_no"=>"手机号","phone_image_id"=>"手机图片","mchid"=>"微信支付商户号","apikey"=>"微信支付密钥","shop_id"=>"店铺","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Wxapp $wxapp
     * @return mixed
     */
    public function index(Request $request, Wxapp $wxapp)
    {
        return $this->backend_view('wxapps.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Wxapp::query();

        $queryFields = $request->get('queryFields');

        if ( $queryFields['shop_nick']){
            if ($tmp = Shop::where('shop_nick',$queryFields['shop_nick'])->get()){
                $shop_id = @$tmp[0]->id;
                unset($queryFields['shop_nick']);
                $queryFields['shop_id'] = $shop_id;
            }

        }
        //查询条件处理
        if ($queryFields) {
            foreach ($queryFields as $field => $value) {
               if(!empty($value)){
                   if (strpos($field, 'name') !== false || $field == 'app_id' || $field == 'app_secret' || $field == 'mchid' ||  $field == 'apikey' ) {
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
        $data = $query->with(['shop'])->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Wxapp $wxapp
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Wxapp $wxapp)
    {

        return $this->backend_view('wxapps.create_edit', compact('wxapp'));
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
            $model = Wxapp::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Wxapp $wxapp
     * @return mixed
     */
    public function edit(Wxapp $wxapp)
    {
        $wxapp = Wxapp::where('id',$wxapp['id'])->with(['shop'])->get()[0];
        $wxapp->shop_nick = $wxapp->shop->shop_nick;
        return $this->backend_view('wxapps.create_edit', compact('wxapp'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Wxapp $wxapp
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Wxapp $wxapp, Result $result)
    {
        try {
            $wxapp->update($request->only($this->fields));
            $result->succeed($wxapp);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Wxapp $wxapp
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Wxapp $wxapp, Result $result)
    {
        if (!$wxapp) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $wxapp->delete();
            if ($del) {
                $result->succeed($wxapp);
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
            $dels = Wxapp::whereIn('id', $ids)->delete();
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

//## 路由：Wxapp
//$router->get('wxapps', 'WxappsController@index')->name('admin.wxapps');
//$router->get('wxapps/create', 'WxappsController@create')->name('admin.wxapps.create');
//$router->get('wxapps/list', 'WxappsController@list')->name('admin.wxapps.list');
//$router->post('wxapps/store', 'WxappsController@store')->name('admin.wxapps.store');
//$router->get('wxapps/edit/{wxapp}', 'WxappsController@edit')->name('admin.wxapps.edit');//隐式绑定
//$router->post('wxapps/update/{wxapp}', 'WxappsController@update')->name('admin.wxapps.update');//隐式绑定
//$router->get('wxapps/destroy/{wxapp}', 'WxappsController@destroy')->name('admin.wxapps.destroy');//隐式绑定
//$router->post('wxapps/destroyBat', 'WxappsController@destroyBat')->name('admin.wxapps.destroyBat');

}
