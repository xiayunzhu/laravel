<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Ml\Response\Result;

class LoginLogsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['address', 'browser', 'device', 'device_type', 'ip', 'language', 'login_time', 'platform', 'user_id', 'user_name'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["address" => "登录地址", "browser" => "浏览器", "created_at" => "创建时间", "device" => "设备名称", "device_type" => "设备类型", "id" => "ID", "ip" => "登录IP", "language" => "语言", "login_time" => "登录时间", "platform" => "操作系统", "updated_at" => "更新时间", "user_id" => "用户ID", "user_name" => "用户昵称"];

    /**
     * 列表
     *
     * @param Request $request
     * @param LoginLog $loginLog
     * @return mixed
     */
    public function index(Request $request, LoginLog $loginLog)
    {
        return $this->backend_view('loginLogs.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = LoginLog::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
            $likeFields = ['address'];
            foreach ($queryFields as $field => $value) {
                if (!empty($value)) {
                    if (strpos($field, 'name') !== false || in_array($field, $likeFields)) {
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
     * @param LoginLog $loginLog
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(LoginLog $loginLog)
    {

        return $this->backend_view('loginLogs.create_edit', compact('loginLog'));
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
            $model = LoginLog::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param LoginLog $loginLog
     * @return mixed
     */
    public function edit(LoginLog $loginLog)
    {

        return $this->backend_view('loginLogs.create_edit', compact('loginLog'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param LoginLog $loginLog
     * @param Result $result
     * @return array
     */
    public function update(Request $request, LoginLog $loginLog, Result $result)
    {
        try {
            $loginLog->update($request->only($this->fields));
            $result->succeed($loginLog);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param LoginLog $loginLog
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(LoginLog $loginLog, Result $result)
    {
        if (!$loginLog) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $loginLog->delete();
            if ($del) {
                $result->succeed($loginLog);
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
            $dels = LoginLog::whereIn('id', $ids)->delete();
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

//## 路由：LoginLog
//$router->get('loginLogs', 'LoginLogsController@index')->name('admin.loginLogs');
//$router->get('loginLogs/create', 'LoginLogsController@create')->name('admin.loginLogs.create');
//$router->get('loginLogs/list', 'LoginLogsController@list')->name('admin.loginLogs.list');
//$router->post('loginLogs/store', 'LoginLogsController@store')->name('admin.loginLogs.store');
//$router->get('loginLogs/edit/{loginLog}', 'LoginLogsController@edit')->name('admin.loginLogs.edit');//隐式绑定
//$router->post('loginLogs/update/{loginLog}', 'LoginLogsController@update')->name('admin.loginLogs.update');//隐式绑定
//$router->get('loginLogs/destroy/{loginLog}', 'LoginLogsController@destroy')->name('admin.loginLogs.destroy');//隐式绑定
//$router->post('loginLogs/destroyBat', 'LoginLogsController@destroyBat')->name('admin.loginLogs.destroyBat');

}
