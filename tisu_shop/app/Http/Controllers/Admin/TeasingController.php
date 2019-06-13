<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teasing;
use App\Models\User;
use Illuminate\Http\Request;
use Ml\Response\Result;

class TeasingController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['title', 'content'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "title" => "标题", "content" => "内容", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Teasing $teasing
     * @return mixed
     */
    public function index(Request $request, Teasing $teasing)
    {
        // dd($teasing);
        return $this->backend_view('teasings.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Teasing::query();
        $queryFields = $request->get('queryFields');

        if($queryFields['user']){
            $name=trim($queryFields['user']);
            $user_id=User::where('name','like', '%' . $name . '%')->get(['id']);
            unset($queryFields['user']);
        }
        //查询条件处理
        if ($queryFields) {
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
        if(!empty($user_id)){
            $query->whereIn('user_id',$user_id);
        }
        $query->with('user')->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Teasing $teasing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Teasing $teasing)
    {

        return $this->backend_view('teasings.create_edit', compact('teasing'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $teasing = Teasing::where('id', $request->get('id'))->with(['teasingImg', 'user'])->first();
        $imgs = $teasing->teasingImg;
        unset($teasing->teasingImg);
        return $this->backend_view('teasings.detail', compact('teasing', 'imgs'));
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
            $model = Teasing::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Teasing $teasing
     * @return mixed
     */
    public function edit(Teasing $teasing)
    {

        return $this->backend_view('teasings.create_edit', compact('teasing'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Teasing $teasing
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Teasing $teasing, Result $result)
    {
        try {
            $teasing->update($request->only($this->fields));
            $result->succeed($teasing);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Teasing $teasing
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Teasing $teasing, Result $result)
    {
        if (!$teasing) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $teasing->delete();
            if ($del) {
                $result->succeed($teasing);
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
            $dels = Teasing::whereIn('id', $ids)->delete();
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

//## 路由：Teasing
//$router->get('teasings', 'TeasingController@index')->name('admin.teasings');
//$router->get('teasings/create', 'TeasingController@create')->name('admin.teasings.create');
//$router->get('teasings/list', 'TeasingController@list')->name('admin.teasings.list');
//$router->post('teasings/store', 'TeasingController@store')->name('admin.teasings.store');
//$router->get('teasings/edit/{teasing}', 'TeasingController@edit')->name('admin.teasings.edit');//隐式绑定
//$router->post('teasings/update/{teasing}', 'TeasingController@update')->name('admin.teasings.update');//隐式绑定
//$router->get('teasings/destroy/{teasing}', 'TeasingController@destroy')->name('admin.teasings.destroy');//隐式绑定
//$router->post('teasings/destroyBat', 'TeasingController@destroyBat')->name('admin.teasings.destroyBat');

}
