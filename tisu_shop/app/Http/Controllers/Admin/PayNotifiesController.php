<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\PayHandler;
use App\Lib\Wx\MinPay\Exception\MiniPayException;
use App\Models\Order;
use App\Models\PayNotify;
use App\Models\PayRefund;
use App\Models\Wxapp;
use Illuminate\Http\Request;
use Ml\Response\Result;

class PayNotifiesController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['appid', 'attach', 'bank_type', 'cash_fee', 'cash_fee_type', 'coupon_count', 'coupon_fee', 'coupon_fee_$n', 'coupon_id_$n', 'coupon_type_$n', 'device_info', 'err_code', 'err_code_des', 'fee_type', 'is_subscribe', 'mch_id', 'nonce_str', 'openid', 'out_trade_no', 'result_code', 'return_msg', 'settlement_total_fee', 'sign', 'time_end', 'total_fee', 'trade_type', 'transaction_id'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["appid" => "小程序ID", "attach" => "商家数据包", "bank_type" => "付款银行", "cash_fee" => "现金支付金额", "cash_fee_type" => "现金支付货币类型", "coupon_count" => "代金券使用数量", "coupon_fee" => "总代金券金额", 'coupon_fee_$n' => "单个代金券支付金额", 'coupon_id_$n' => "代金券ID", 'coupon_type_$n' => "代金券类型", "created_at" => "创建时间", "device_info" => "设备号	", "err_code" => "错误代码", "err_code_des" => "错误代码描述	", "fee_type" => "货币种类", "id" => "ID", "is_subscribe" => "是否关注公众账号,Y-关注，N-未关注", "mch_id" => "商户号", "nonce_str" => "随机字符串", "openid" => "用户标识", "out_trade_no" => "商户订单号", "result_code" => "业务结果", "return_msg" => "返回信息", "settlement_total_fee" => "应结订单金额", "sign" => "签名", "time_end" => "支付完成时间", "total_fee" => "订单总金额，单位为分	", "trade_type" => "交易类型	", "transaction_id" => "微信支付订单号", "updated_at" => "更新时间"];

    /**
     * 列表
     *
     * @param Request $request
     * @param PayNotify $payNotify
     * @return mixed
     */
    public function index(Request $request, PayNotify $payNotify)
    {
        return $this->backend_view('payNotifies.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = PayNotify::query();

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
     * @param PayNotify $payNotify
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(PayNotify $payNotify)
    {

        return $this->backend_view('payNotifies.create_edit', compact('payNotify'));
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
            $model = PayNotify::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param PayNotify $payNotify
     * @return mixed
     */
    public function edit(PayNotify $payNotify)
    {

        return $this->backend_view('payNotifies.create_edit', compact('payNotify'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param PayNotify $payNotify
     * @param Result $result
     * @return array
     */
    public function update(Request $request, PayNotify $payNotify, Result $result)
    {
        try {
            $payNotify->update($request->only($this->fields));
            $result->succeed($payNotify);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param PayNotify $payNotify
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(PayNotify $payNotify, Result $result)
    {
        if (!$payNotify) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $payNotify->delete();
            if ($del) {
                $result->succeed($payNotify);
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
            $dels = PayNotify::whereIn('id', $ids)->delete();
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
     * @param PayNotify $payNotify
     * @param Result $result
     * @param PayHandler $payHandler
     * @return array
     * @throws \App\Lib\Wx\MinPay\Exception\MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function refund(PayNotify $payNotify, Result $result, PayHandler $payHandler)
    {

        $order = Order::where('order_no', '=', $payNotify->out_trade_no)->first();
        $shop_id = $order->shop_id;
        if (empty($shop_id)) {
            throw new MiniPayException('店铺不明确');
        }

        $wxapp = Wxapp::where('shop_id', $shop_id)->first();
        if (empty($wxapp)) {
            throw new MiniPayException('商城信息未查到');
        }

        $data = $payHandler->refund($wxapp,
            [
                'transaction_id' => $payNotify->transaction_id,
                'out_trade_no' => $payNotify->out_trade_no,
                'out_refund_no' => $payNotify->out_trade_no,
                'total_fee' => $payNotify->total_fee,
                'refund_fee' => $payNotify->total_fee
            ]
        );

        //todo 改为异步
        if ($data && is_array($data))
            PayRefund::create($data);

        $result->succeed($data);

        return $result->toArray();
    }

//## 路由：PayNotify
//$router->get('payNotifies', 'PayNotifiesController@index')->name('admin.payNotifies');
//$router->get('payNotifies/create', 'PayNotifiesController@create')->name('admin.payNotifies.create');
//$router->get('payNotifies/list', 'PayNotifiesController@list')->name('admin.payNotifies.list');
//$router->post('payNotifies/store', 'PayNotifiesController@store')->name('admin.payNotifies.store');
//$router->get('payNotifies/edit/{payNotify}', 'PayNotifiesController@edit')->name('admin.payNotifies.edit');//隐式绑定
//$router->post('payNotifies/update/{payNotify}', 'PayNotifiesController@update')->name('admin.payNotifies.update');//隐式绑定
//$router->get('payNotifies/destroy/{payNotify}', 'PayNotifiesController@destroy')->name('admin.payNotifies.destroy');//隐式绑定
//$router->post('payNotifies/destroyBat', 'PayNotifiesController@destroyBat')->name('admin.payNotifies.destroyBat');

}
