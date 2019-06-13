<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use Illuminate\Http\Request;
use Ml\Response\Result;

class FilesController extends BaseController
{
 /**
    /**
     * 字段
     * @var array
     */
    private $fields = ['type','path','mime_type','md5','title','folder','object_id','size','width','height','downloads','public','editor','status','created_op'];

    /**
     * 列表
     *
     * @param Request $request
     * @param File $file
     * @return mixed
     */
    public function index(Request $request, File $file)
    {
        return $this->backend_view('files.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = File::query();

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : config('admin.paginate.limit');
        if ($per_page > 100) {
            //限制最大100
            $per_page = 100;
        }
        $query = $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param File $file
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(File $file)
    {

        return $this->backend_view('files.create_edit', compact('file'));
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
            $model = File::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param File $file
     * @return mixed
     */
    public function edit(File $file)
    {

        return $this->backend_view('files.create_edit', compact('file'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param File $file
     * @param Result $result
     * @return array
     */
    public function update(Request $request, File $file, Result $result)
    {
        $file->update($request->only($this->fields));
        $result->succeed($file);

        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param File $file
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(File $file, Result $result)
    {
        if (!$file) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $file->delete();
            if ($del) {
                $result->succeed($file);
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
            $dels = File::whereIn('id', $ids)->delete();
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

//## 路由：{model}
//$router->get('files', 'FilesController@index')->name('admin.files');
//$router->get('files/create', 'FilesController@create')->name('admin.files.create');
//$router->get('files/list', 'FilesController@list')->name('admin.files.list');
//$router->post('files/store', 'FilesController@store')->name('admin.files.store');
//$router->get('files/edit/{file}', 'FilesController@edit')->name('admin.files.edit');//隐式绑定
//$router->post('files/update/{file}', 'FilesController@update')->name('admin.files.update');//隐式绑定
//$router->get('files/destroy/{file}', 'FilesController@destroy')->name('admin.files.destroy');//隐式绑定
//$router->post('files/destroyBat', 'FilesController@destroyBat')->name('admin.files.destroyBat');

}
