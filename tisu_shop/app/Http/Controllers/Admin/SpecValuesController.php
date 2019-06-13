<?php

namespace App\Http\Controllers\Admin;

use App\Models\Specs;
use App\Models\SpecValues;
use Illuminate\Http\Request;
use Ml\Response\Result;


class SpecValuesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['spec_value', 'spec_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "spec_value" => "标准值", "spec_id" => "斯皮特", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param SpecValues $specValues
     * @return mixed
     */
    public function index(Request $request, SpecValues $specValues)
    {

        return $this->backend_view('specValues.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = SpecValues::query();

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
        $data->load('spec');
//        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param SpecValues $specValues
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(SpecValues $specValues)
    {
        $spec = Specs::all();
        return $this->backend_view('specValues.create_edit', compact('specValues', 'spec'));
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
            $model = SpecValues::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param SpecValues $specValues
     * @return mixed
     */
    public function edit(SpecValues $specValues)
    {
        $spec = Specs::all();
        return $this->backend_view('specValues.create_edit', compact('specValues', 'spec'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param SpecValues $specValues
     * @param Result $result
     * @return array
     */
    public function update(Request $request, SpecValues $specValues, Result $result)
    {
        try {
            $specValues->update($request->only($this->fields));
            $result->succeed($specValues);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param SpecValues $specValues
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(SpecValues $specValues, Result $result)
    {
        if (!$specValues) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $specValues->delete();
            if ($del) {
                $result->succeed($specValues);
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
            $dels = SpecValues::whereIn('id', $ids)->delete();
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


}
