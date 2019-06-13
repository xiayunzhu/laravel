<?php

namespace App\Http\Controllers\Admin;

use App\Models\OperationLog;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OperationLogsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['uid','path','method','ip','sql','input'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","uid"=>"UID","path"=>"路径","method"=>"方法","ip"=>"知识产权","sql"=>"SQL","input"=>"输入","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OperationLog $operationLog
     * @return mixed
     */
    public function index(Request $request, OperationLog $operationLog)
    {
        return $this->backend_view('operationLogs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OperationLog::query();

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
     * @param OperationLog $operationLog
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OperationLog $operationLog)
    {

        return $this->backend_view('operationLogs.create_edit', compact('operationLog'));
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
            $model = OperationLog::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OperationLog $operationLog
     * @return mixed
     */
    public function edit(OperationLog $operationLog)
    {

        return $this->backend_view('operationLogs.create_edit', compact('operationLog'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OperationLog $operationLog
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OperationLog $operationLog, Result $result)
    {
        try {
            $operationLog->update($request->only($this->fields));
            $result->succeed($operationLog);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OperationLog $operationLog
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OperationLog $operationLog, Result $result)
    {
        if (!$operationLog) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $operationLog->delete();
            if ($del) {
                $result->succeed($operationLog);
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
            $dels = OperationLog::whereIn('id', $ids)->delete();
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

//## 路由：OperationLog
//$router->get('operationLogs', 'OperationLogsController@index')->name('admin.operationLogs');
//$router->get('operationLogs/create', 'OperationLogsController@create')->name('admin.operationLogs.create');
//$router->get('operationLogs/list', 'OperationLogsController@list')->name('admin.operationLogs.list');
//$router->post('operationLogs/store', 'OperationLogsController@store')->name('admin.operationLogs.store');
//$router->get('operationLogs/edit/{operationLog}', 'OperationLogsController@edit')->name('admin.operationLogs.edit');//隐式绑定
//$router->post('operationLogs/update/{operationLog}', 'OperationLogsController@update')->name('admin.operationLogs.update');//隐式绑定
//$router->get('operationLogs/destroy/{operationLog}', 'OperationLogsController@destroy')->name('admin.operationLogs.destroy');//隐式绑定
//$router->post('operationLogs/destroyBat', 'OperationLogsController@destroyBat')->name('admin.operationLogs.destroyBat');

}
