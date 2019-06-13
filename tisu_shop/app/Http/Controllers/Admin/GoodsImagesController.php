<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoodsImage;
use Illuminate\Http\Request;
use Ml\Response\Result;

class GoodsImagesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['goods_id','image_id','file_url','property','sort','shop_id','create_time'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id"=>"ID","goods_id"=>"古德萨德","image_id"=>"图像标识","file_url"=>"文件网址","property"=>"财产","sort"=>"分类","shop_id"=>"购物狂","create_time"=>"创建时间","created_at"=>"创建时间","updated_at"=>"更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param GoodsImage $goodsImage
     * @return mixed
     */
    public function index(Request $request, GoodsImage $goodsImage)
    {
        return $this->backend_view('goodsImages.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = GoodsImage::query();

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
     * @param GoodsImage $goodsImage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(GoodsImage $goodsImage)
    {

        return $this->backend_view('goodsImages.create_edit', compact('goodsImage'));
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
            $model = GoodsImage::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param GoodsImage $goodsImage
     * @return mixed
     */
    public function edit(GoodsImage $goodsImage)
    {

        return $this->backend_view('goodsImages.create_edit', compact('goodsImage'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param GoodsImage $goodsImage
     * @param Result $result
     * @return array
     */
    public function update(Request $request, GoodsImage $goodsImage, Result $result)
    {
        try {
            $goodsImage->update($request->only($this->fields));
            $result->succeed($goodsImage);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param GoodsImage $goodsImage
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(GoodsImage $goodsImage, Result $result)
    {
        if (!$goodsImage) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $goodsImage->delete();
            if ($del) {
                $result->succeed($goodsImage);
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
            $dels = GoodsImage::whereIn('id', $ids)->delete();
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

//## 路由：GoodsImage
//$router->get('goodsImages', 'GoodsImagesController@index')->name('admin.goodsImages');
//$router->get('goodsImages/create', 'GoodsImagesController@create')->name('admin.goodsImages.create');
//$router->get('goodsImages/list', 'GoodsImagesController@list')->name('admin.goodsImages.list');
//$router->post('goodsImages/store', 'GoodsImagesController@store')->name('admin.goodsImages.store');
//$router->get('goodsImages/edit/{goodsImage}', 'GoodsImagesController@edit')->name('admin.goodsImages.edit');//隐式绑定
//$router->post('goodsImages/update/{goodsImage}', 'GoodsImagesController@update')->name('admin.goodsImages.update');//隐式绑定
//$router->get('goodsImages/destroy/{goodsImage}', 'GoodsImagesController@destroy')->name('admin.goodsImages.destroy');//隐式绑定
//$router->post('goodsImages/destroyBat', 'GoodsImagesController@destroyBat')->name('admin.goodsImages.destroyBat');

}
