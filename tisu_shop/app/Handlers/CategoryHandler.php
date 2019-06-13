<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/2/22
 * Time: 17:50
 */

namespace App\Handlers;


use App\Models\Category;

class CategoryHandler
{

    /**
     * @return mixed
     */
    public static function parentCategories()
    {
        return Category::where('parent_id', 0)->get()->pluck('name', 'id');
    }

    /**
     * 一级分类
     *
     * @return mixed
     */
    public static function stairCategories(){
        return Category::where('parent_id', 0)->select(['id', 'name'])->get();
    }

    /**
     * 返回当前分类及子分类ID的集合
     *
     * @param $id
     * @return array
     */
    public static function searchCategories($id){
        $parentId = Category::find($id);
        $parentId->load('childrens');
        $categoryIds[] = $parentId->id;
        foreach ($parentId->childrens as $key=>$children){
            $categoryIds[] = $children['id'];
        }
        return $categoryIds;
    }

    /**
     * 分类分级
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function groupData()
    {
        $query = Category::query();
        $query->where('parent_id', '=', 0);
        $query->with('children');
        $query->orderBy('parent_id', 'asc');
        $query->orderBy('sort', 'asc');
        $data = $query->get();
        return $data;
    }

    /**
     * 品类的下拉框选择
     * @return array
     */
    public static function options()
    {
        $options = [];
        $query = Category::query();
        $query->where('parent_id', '=', 0);
        $query->with('children');
        $query->orderBy('parent_id', 'asc');
        $query->orderBy('sort', 'asc');
        $data = $query->get();
        if ($data) {
            $rows = $data->toArray();
            foreach ($rows as $row) {
                $children = $row['children'];
                unset($row['children']);
                $options[] = $row;
                $options = array_merge($options, $children);
            }
        }

        return $options;
    }

    /**
     * 返回当前类别父类信息
     *
     * @param $id
     * @return mixed
     */
    public static function parentCategory($id){
        $category = Category::find($id);
        if (!$category->parent_id)
            return $category;
        $parentCategory = Category::find($category->parent_id);
        return $parentCategory;
    }
}