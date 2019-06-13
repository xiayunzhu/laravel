<?php

namespace App\Http\Controllers\Admin;

use App\Models\UploadFile;
use App\Models\UploadGroup;
use Illuminate\Http\Request;
use Ml\Response\Result;

class UploadFilesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['folder','object_id','group_id','path','file_url','file_name','file_size','file_type','extension','shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","folder"=>"文件对象类型","object_id"=>"文件对象ID","group_id"=>"文件分组ID","path"=>"文件路径","file_url"=>"文件路径","file_name"=>"文件名称","file_size"=>"文件大小","file_type"=>"文件类型","extension"=>"文件扩展名","shop_id"=>"店铺ID","deleted_at"=>"删除时间","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param UploadFile $uploadFile
     * @return mixed
     */
    public function index(Request $request, UploadFile $uploadFile)
    {
        return $this->backend_view('uploadFiles.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = UploadFile::query();

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
     * @param Request $request
     * @param UploadFile $uploadFile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(UploadFile $uploadFile,Request $request)
    {
        $groups = UploadGroup::orderBy('sort','asc')->get();
        $group_id = $request->get('group_id');

        if ($group_id == ''|| $group_id == -1){
            $uploadFile = UploadFile::orderBy('id','desc')->paginate(18);
        }else{
            $uploadFile = UploadFile::where('group_id',$group_id)->orderBy('id','desc')->paginate(18);
        }

        return $this->backend_view('uploadFiles.create_edit', compact('uploadFile','groups','group_id'));
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
            $model = UploadFile::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param UploadFile $uploadFile
     * @return mixed
     */
    public function edit(UploadFile $uploadFile)
    {

        return $this->backend_view('uploadFiles.create_edit', compact('uploadFile'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param UploadFile $uploadFile
     * @param Result $result
     * @return array
     */
    public function update(Request $request, UploadFile $uploadFile, Result $result)
    {
        try {
            $uploadFile->update($request->only($this->fields));
            $result->succeed($uploadFile);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param UploadFile $uploadFile
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(UploadFile $uploadFile, Result $result)
    {
        if (!$uploadFile) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $uploadFile->delete();
            if ($del) {
                $result->succeed($uploadFile);
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
            $dels = UploadFile::whereIn('id', $ids)->delete();
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
    /**
     * 批量更新
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function updateBat(Request $request, Result $result)
    {
        $ids = $request->get('ids');
        $group_id = $request->get('group_id');
        if ($ids && is_array($ids)) {
            $upds = UploadFile::whereIn('id', $ids)->update(['group_id' => $group_id]);
            if ($upds > 0) {
                $result->succeed();
            } else {
                $result->failed('移动失败');
            }
        } else {
            $result->failed('参数错误');
        }

        return $result->toArray();
    }
    /**
     * 图片分组查询
     * @param Request $request
     * @return mixed
     */
    public function picGroup(Request $request){

        $group_id = $request->get('group_id');

        $uploadFile = UploadFile::where('group_id',$group_id)->orderBy('id','desc')->paginate(18);

        return $this->backend_view('uploadFiles.create_edit', compact('uploadFile'));
    }
    /**
     * 选择图片的的窗口页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function picWindow(Request $request)
    {
        $groups = UploadGroup::orderBy('sort','asc')->get();
        $group_id = $request->get('group_id');

        if ($group_id == ''|| $group_id == -1){
            $uploadFile = UploadFile::orderBy('id','desc')->paginate(8);
        }else{
            $uploadFile = UploadFile::where('group_id',$group_id)->orderBy('id','desc')->paginate(8);
        }

        return $this->backend_view('uploadFiles.pic_window', compact('uploadFile','groups','group_id'));
    }


//## 路由：UploadFile
//$router->get('uploadFiles', 'UploadFilesController@index')->name('admin.uploadFiles');
//$router->get('uploadFiles/create', 'UploadFilesController@create')->name('admin.uploadFiles.create');
//$router->get('uploadFiles/list', 'UploadFilesController@list')->name('admin.uploadFiles.list');
//$router->post('uploadFiles/store', 'UploadFilesController@store')->name('admin.uploadFiles.store');
//$router->get('uploadFiles/edit/{uploadFile}', 'UploadFilesController@edit')->name('admin.uploadFiles.edit');//隐式绑定
//$router->post('uploadFiles/update/{uploadFile}', 'UploadFilesController@update')->name('admin.uploadFiles.update');//隐式绑定
//$router->get('uploadFiles/destroy/{uploadFile}', 'UploadFilesController@destroy')->name('admin.uploadFiles.destroy');//隐式绑定
//$router->post('uploadFiles/destroyBat', 'UploadFilesController@destroyBat')->name('admin.uploadFiles.destroyBat');

}
