<?php

namespace App\Http\Controllers\Admin;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Ml\Response\Result;

class BuyersController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['open_id','phone','union_id','nick_name','avatar_url','gender','remark','source','language','country','province','city','address_id','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","open_id"=>"用户小程序的open_id","phone"=>"手机号","union_id"=>"微信小程序的 unionId","nick_name"=>"微信昵称","avatar_url"=>"头像链接","gender"=>"性别","remark"=>"备注","source"=>"来源","language"=>"语言","country"=>"国家","province"=>"省","city"=>"市","address_id"=>"地址ID","shop_id"=>"店铺ID","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Buyer $buyer
     * @return mixed
     */
    public function index(Request $request, Buyer $buyer)
    {
        return $this->backend_view('buyers.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Buyer::query();

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
//        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Buyer $buyer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Buyer $buyer)
    {

        return $this->backend_view('buyers.create_edit', compact('buyer'));
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
            $model = Buyer::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Buyer $buyer
     * @return mixed
     */
    public function edit(Buyer $buyer)
    {

        return $this->backend_view('buyers.create_edit', compact('buyer'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Buyer $buyer
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Buyer $buyer, Result $result)
    {
        try {
            $buyer->update($request->only($this->fields));
            $result->succeed($buyer);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Buyer $buyer
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Buyer $buyer, Result $result)
    {
        if (!$buyer) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $buyer->delete();
            if ($del) {
                $result->succeed($buyer);
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
            $dels = Buyer::whereIn('id', $ids)->delete();
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

//## 路由：Buyer
//$router->get('buyers', 'BuyersController@index')->name('admin.buyers');
//$router->get('buyers/create', 'BuyersController@create')->name('admin.buyers.create');
//$router->get('buyers/list', 'BuyersController@list')->name('admin.buyers.list');
//$router->post('buyers/store', 'BuyersController@store')->name('admin.buyers.store');
//$router->get('buyers/edit/{buyer}', 'BuyersController@edit')->name('admin.buyers.edit');//隐式绑定
//$router->post('buyers/update/{buyer}', 'BuyersController@update')->name('admin.buyers.update');//隐式绑定
//$router->get('buyers/destroy/{buyer}', 'BuyersController@destroy')->name('admin.buyers.destroy');//隐式绑定
//$router->post('buyers/destroyBat', 'BuyersController@destroyBat')->name('admin.buyers.destroyBat');

}
