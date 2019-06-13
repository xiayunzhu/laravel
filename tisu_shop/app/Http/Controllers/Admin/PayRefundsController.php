<?php

namespace App\Http\Controllers\Admin;

use App\Models\PayRefund;
use Illuminate\Http\Request;
use Ml\Response\Result;

class PayRefundsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['return_code', 'err_code', 'err_code_des', 'appid', 'mch_id', 'nonce_str', 'sign', 'transaction_id', 'out_trade_no', 'out_refund_no', 'refund_id', 'refund_fee', 'settlement_refund_fee', 'total_fee', 'settlement_total_fee', 'fee_type', 'cash_fee', 'cash_fee_type', 'cash_refund_fee', 'coupon_type_$n', 'coupon_refund_fee', 'coupon_refund_fee_$n', 'coupon_refund_count', 'coupon_refund_id_$n', 'return_msg'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["id" => "ID", "return_code" => "返回状态码", "err_code" => "错误代码", "err_code_des" => "错误代码描述", "appid" => "小程序ID", "mch_id" => "商户号", "nonce_str" => "随机字符串", "sign" => "签名", "transaction_id" => "微信订单号", "out_trade_no" => "商户订单号	", "out_refund_no" => "商户退款单号", "refund_id" => "微信退款单号", "refund_fee" => "退款总金额,单位为分,可以做部分退款", "settlement_refund_fee" => "应结退款金额", "total_fee" => "标价金额", "settlement_total_fee" => "应结订单金额", "fee_type" => "标价币种", "cash_fee" => "现金支付金额", "cash_fee_type" => "现金支付币种", "cash_refund_fee" => "现金退款金额", 'coupon_type_$n' => "代金券类型", "coupon_refund_fee" => "代金券退款总金额", 'coupon_refund_fee_$n' => "单个代金券退款金额", "coupon_refund_count" => "退款代金券使用数量", 'coupon_refund_id_$n' => "退款代金券ID", "return_msg" => "返回信息", "created_at" => "创建时间", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param PayRefund $payRefund
     * @return mixed
     */
    public function index(Request $request, PayRefund $payRefund)
    {
        return $this->backend_view('payRefunds.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = PayRefund::query();

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
     * 新增页面
     * @param PayRefund $payRefund
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(PayRefund $payRefund)
    {

        return $this->backend_view('payRefunds.create_edit', compact('payRefund'));
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
            $model = PayRefund::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param PayRefund $payRefund
     * @return mixed
     */
    public function edit(PayRefund $payRefund)
    {

        return $this->backend_view('payRefunds.create_edit', compact('payRefund'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param PayRefund $payRefund
     * @param Result $result
     * @return array
     */
    public function update(Request $request, PayRefund $payRefund, Result $result)
    {
        try {
            $payRefund->update($request->only($this->fields));
            $result->succeed($payRefund);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param PayRefund $payRefund
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(PayRefund $payRefund, Result $result)
    {
        if (!$payRefund) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $payRefund->delete();
            if ($del) {
                $result->succeed($payRefund);
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
            $dels = PayRefund::whereIn('id', $ids)->delete();
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

//## 路由：PayRefund
//$router->get('payRefunds', 'PayRefundsController@index')->name('admin.payRefunds');
//$router->get('payRefunds/create', 'PayRefundsController@create')->name('admin.payRefunds.create');
//$router->get('payRefunds/list', 'PayRefundsController@list')->name('admin.payRefunds.list');
//$router->post('payRefunds/store', 'PayRefundsController@store')->name('admin.payRefunds.store');
//$router->get('payRefunds/edit/{payRefund}', 'PayRefundsController@edit')->name('admin.payRefunds.edit');//隐式绑定
//$router->post('payRefunds/update/{payRefund}', 'PayRefundsController@update')->name('admin.payRefunds.update');//隐式绑定
//$router->get('payRefunds/destroy/{payRefund}', 'PayRefundsController@destroy')->name('admin.payRefunds.destroy');//隐式绑定
//$router->post('payRefunds/destroyBat', 'PayRefundsController@destroyBat')->name('admin.payRefunds.destroyBat');

}
