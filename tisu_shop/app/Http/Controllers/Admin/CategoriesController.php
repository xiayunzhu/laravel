<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\CategoryHandler;
use App\Models\Category;
use Illuminate\Http\Request;
use Ml\Response\Result;

class CategoriesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['code', 'name', 'parent_id', 'image_url', 'sort', 'introduction'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "code" => "分类标识", "name" => "名称", "parent_id" => "亲本", "image_url" => "图像网址", "sort" => "分类", "introduction" => "介绍", "deleted_at" => "删除时间", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * @var CategoryHandler
     */
    private $handler;

    public function __construct(CategoryHandler $handler)
    {
        $this->handler = $handler;
    }


    /**
     * 列表
     *
     * @param Request $request
     * @param Category $category
     * @return mixed
     */
    public function index(Request $request, Category $category)
    {
        return $this->backend_view('categories.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Category::query();

        //查询条件处理
        if ($queryFields = $request->get('queryFields')) {
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
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
//        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 获取所有品类和其子品类
     * @param Result $result
     * @return array
     */
    public function categoryAll(Result $result)
    {
        $data = $this->handler->groupData();
        return $result->succeed($data)->toArray();
    }

    /**
     * 新增页面
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Category $category)
    {

        return $this->backend_view('categories.create_edit', compact('category'));
    }

    /**
     * 创建品类的窗口页面
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createWindow(Category $category)
    {
        return $this->backend_view('categories.create_window', compact('category'));
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
            $data = $request->only($this->fields);
            $model = Category::create($data);
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Category $category
     * @return mixed
     */
    public function edit(Category $category)
    {

        return $this->backend_view('categories.create_edit', compact('category'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Category $category
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Category $category, Result $result)
    {
        try {
            $category->update($request->only($this->fields));
            $result->succeed($category);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Category $category
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Category $category, Result $result)
    {
        if (!$category) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $category->delete();
            if ($del) {
                $result->succeed($category);
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
            $dels = Category::whereIn('id', $ids)->delete();
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

//## 路由：Category
//$router->get('categories', 'CategoriesController@index')->name('admin.categories');
//$router->get('categories/create', 'CategoriesController@create')->name('admin.categories.create');
//$router->get('categories/list', 'CategoriesController@list')->name('admin.categories.list');
//$router->post('categories/store', 'CategoriesController@store')->name('admin.categories.store');
//$router->get('categories/edit/{category}', 'CategoriesController@edit')->name('admin.categories.edit');//隐式绑定
//$router->post('categories/update/{category}', 'CategoriesController@update')->name('admin.categories.update');//隐式绑定
//$router->get('categories/destroy/{category}', 'CategoriesController@destroy')->name('admin.categories.destroy');//隐式绑定
//$router->post('categories/destroyBat', 'CategoriesController@destroyBat')->name('admin.categories.destroyBat');

}
