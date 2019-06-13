<?php

namespace App\Http\Controllers\Admin;

use App\Models\Refund;
use App\Models\Shop;
use Illuminate\Http\Request;
use Ml\Response\Result;

class RefundsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['order_no', 'item_no', 'user_id', 'goods_id', 'goods_spec_id', 'refund_no', 'refund_way', 'refund_reason', 'back_money', 'phone', 'remark', 'image_urls', 'status', 'refund_progress', 'arrive_time', 'shop_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "order_no" => "主订单编号", "item_no" => "子订单编号", "user_id" => "退款用户ID", "goods_id" => "商品ID", "goods_spec_id" => "商品规格ID", "refund_no" => "退款订单编号", "refund_way" => "处理方式", "refund_reason" => "退款原因", "back_money" => "退款金额", "phone" => "手机号码", "remark" => "备注", "image_urls" => "图片地址", "status" => "退款状态", "refund_progress" => "退款进度", "arrive_time" => "到账时间", "shop_id" => "店铺ID", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param Refund $refund
     * @return mixed
     */
    public function index(Request $request, Refund $refund)
    {
        return $this->backend_view('refunds.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = Refund::query();
        $queryFields = $request->get('queryFields');
        if ($queryFields['shop_name']){
            if ($tmp = Shop::where('shop_nick',$queryFields['shop_name'])->first()){
                $queryFields['shop_id'] = @$tmp->id;
                unset($queryFields['shop_name']);
            }

        }
        //查询条件处理
        if ($queryFields) {
            foreach ($queryFields as $field => $value) {
                if ($value != '') {
                    if (strpos($field, 'name') !== false || strpos($field,'_no') !== false) {
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
        $data->load(['order_item','buyer','order']);
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @param Refund $refund
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Refund $refund)
    {

        return $this->backend_view('refunds.create_edit', compact('refund'));
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
            $model = Refund::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param Refund $refund
     * @return mixed
     */
    public function edit(Refund $refund)
    {

        return $this->backend_view('refunds.create_edit', compact('refund'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param Refund $refund
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Refund $refund, Result $result)
    {
        try {
            $refund->update($request->only($this->fields));
            $result->succeed($refund);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param Refund $refund
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(Refund $refund, Result $result)
    {
        if (!$refund) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $refund->delete();
            if ($del) {
                $result->succeed($refund);
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
            $dels = Refund::whereIn('id', $ids)->delete();
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
     * 详情
     *
     * @param Refund $refund
     * @return mixed
     */
    public function detail(Refund $refund)
    {
        $refund->load(['order_item', 'buyer', 'order']);
        if (!empty($refund['image_urls']))
            $refund->image_urls = unserialize($refund['image_urls']);
        return $this->backend_view('refunds.detail', compact('refund'));
    }


    /**
     * 售后处理
     *
     * @param Request $request
     * @param Refund $refund
     * @param Result $result
     * @return array
     */
    public function operate(Request $request, Refund $refund, Result $result)
    {
        try {
            $refund->update($request->only($this->fields));
            $result->succeed($refund);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

//## 路由：Refund
//$router->get('refunds', 'RefundsController@index')->name('admin.refunds');
//$router->get('refunds/create', 'RefundsController@create')->name('admin.refunds.create');
//$router->get('refunds/list', 'RefundsController@list')->name('admin.refunds.list');
//$router->post('refunds/store', 'RefundsController@store')->name('admin.refunds.store');
//$router->get('refunds/edit/{refund}', 'RefundsController@edit')->name('admin.refunds.edit');//隐式绑定
//$router->post('refunds/update/{refund}', 'RefundsController@update')->name('admin.refunds.update');//隐式绑定
//$router->get('refunds/destroy/{refund}', 'RefundsController@destroy')->name('admin.refunds.destroy');//隐式绑定
//$router->post('refunds/destroyBat', 'RefundsController@destroyBat')->name('admin.refunds.destroyBat');

}
