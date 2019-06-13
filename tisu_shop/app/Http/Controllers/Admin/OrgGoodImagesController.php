<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrgGoodImage;
use Illuminate\Http\Request;
use Ml\Response\Result;

class OrgGoodImagesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['org_goods_id','image_id','file_url','property','sort','create_time'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","org_goods_id"=>"商品ID","image_id"=>"商品图片","file_url"=>"文件路径","property"=>"属性:主图,列表图,详情图","sort"=>"排序","create_time"=>"创建时间","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param OrgGoodImage $orgGoodImage
     * @return mixed
     */
    public function index(Request $request, OrgGoodImage $orgGoodImage)
    {
        return $this->backend_view('orgGoodImages.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = OrgGoodImage::query();

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
     * @param OrgGoodImage $orgGoodImage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(OrgGoodImage $orgGoodImage)
    {

        return $this->backend_view('orgGoodImages.create_edit', compact('orgGoodImage'));
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
            $model = OrgGoodImage::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param OrgGoodImage $orgGoodImage
     * @return mixed
     */
    public function edit(OrgGoodImage $orgGoodImage)
    {

        return $this->backend_view('orgGoodImages.create_edit', compact('orgGoodImage'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param OrgGoodImage $orgGoodImage
     * @param Result $result
     * @return array
     */
    public function update(Request $request, OrgGoodImage $orgGoodImage, Result $result)
    {
        try {
            $orgGoodImage->update($request->only($this->fields));
            $result->succeed($orgGoodImage);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param OrgGoodImage $orgGoodImage
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(OrgGoodImage $orgGoodImage, Result $result)
    {
        if (!$orgGoodImage) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $orgGoodImage->delete();
            if ($del) {
                $result->succeed($orgGoodImage);
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
            $dels = OrgGoodImage::whereIn('id', $ids)->delete();
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

//## 路由：OrgGoodImage
//$router->get('orgGoodImages', 'OrgGoodImagesController@index')->name('admin.orgGoodImages');
//$router->get('orgGoodImages/create', 'OrgGoodImagesController@create')->name('admin.orgGoodImages.create');
//$router->get('orgGoodImages/list', 'OrgGoodImagesController@list')->name('admin.orgGoodImages.list');
//$router->post('orgGoodImages/store', 'OrgGoodImagesController@store')->name('admin.orgGoodImages.store');
//$router->get('orgGoodImages/edit/{orgGoodImage}', 'OrgGoodImagesController@edit')->name('admin.orgGoodImages.edit');//隐式绑定
//$router->post('orgGoodImages/update/{orgGoodImage}', 'OrgGoodImagesController@update')->name('admin.orgGoodImages.update');//隐式绑定
//$router->get('orgGoodImages/destroy/{orgGoodImage}', 'OrgGoodImagesController@destroy')->name('admin.orgGoodImages.destroy');//隐式绑定
//$router->post('orgGoodImages/destroyBat', 'OrgGoodImagesController@destroyBat')->name('admin.orgGoodImages.destroyBat');

}
