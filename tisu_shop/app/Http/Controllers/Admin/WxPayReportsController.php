<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\PayHandler;
use App\Models\Wxapp;
use App\Models\WxPayReport;
use Illuminate\Http\Request;
use Ml\Response\Result;

class WxPayReportsController extends BaseController
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['appid', 'code_url', 'device_info', 'err_code', 'err_code_des', 'interface_url', 'mch_id', 'nonce_str', 'prepay_id', 'result_code', 'return_msg', 'sign', 'trade_type'];

    /**
     * 数据字典
     *
     * @var array
     */
    private $fieldsMap = ["appid" => "小程序ID", "code_url" => "二维码链接", "created_at" => "创建时间", "device_info" => "设备号	", "err_code" => "错误代码", "err_code_des" => "错误代码描述	", "id" => "ID", "interface_url" => "请求链接", "mch_id" => "商户号", "nonce_str" => "随机字符串", "prepay_id" => "预支付交易会话标识", "result_code" => "业务结果", "return_msg" => "返回信息", "sign" => "签名", "trade_type" => "交易类型	", "updated_at" => "更新时间"];

    /**
     * @var PayHandler
     */
    private $payHandler;

    public function __construct(PayHandler $payHandler)
    {
        $this->payHandler = $payHandler;

    }

    /**
     * 列表
     *
     * @param Request $request
     * @param WxPayReport $wxPayReport
     * @return mixed
     */
    public function index(Request $request, WxPayReport $wxPayReport)
    {
        return $this->backend_view('wxPayReports.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = WxPayReport::query();

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
     * @param WxPayReport $wxPayReport
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(WxPayReport $wxPayReport)
    {

        return $this->backend_view('wxPayReports.create_edit', compact('wxPayReport'));
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
            $model = WxPayReport::create($request->only($this->fields));
            $result->succeed($model);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }

    /**
     * 编辑
     *
     * @param WxPayReport $wxPayReport
     * @return mixed
     */
    public function edit(WxPayReport $wxPayReport)
    {

        return $this->backend_view('wxPayReports.create_edit', compact('wxPayReport'));
    }

    /**
     * 更新
     *
     * @param Request $request
     * @param WxPayReport $wxPayReport
     * @param Result $result
     * @return array
     */
    public function update(Request $request, WxPayReport $wxPayReport, Result $result)
    {
        try {
            $wxPayReport->update($request->only($this->fields));
            $result->succeed($wxPayReport);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }
        return $result->toArray();
    }

    /**
     * 删除
     *
     * @param WxPayReport $wxPayReport
     * @param Result $result
     * @return array
     * @throws \Exception
     */
    public function destroy(WxPayReport $wxPayReport, Result $result)
    {
        if (!$wxPayReport) {
            $result->failed('已删除或不存在');
        } else {
            //执行删除
            $del = $wxPayReport->delete();
            if ($del) {
                $result->succeed($wxPayReport);
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
            $dels = WxPayReport::whereIn('id', $ids)->delete();
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
     * @param WxPayReport $wxPayReport
     * @param Result $result
     * @return array
     * @throws \App\Lib\Wx\MinPay\Exception\MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function orderQuery(WxPayReport $wxPayReport, Result $result)
    {
        try {
            $app_id = 'wx7ba43f874a4a6516';
            $wxapp = Wxapp::where('app_id', $app_id)->first();
            if (empty($wxapp)) {
                $this->error('商城不存在');
            }

            $data = $this->payHandler->query($wxapp, null, $wxPayReport->out_trade_no);
            $result->succeed($data);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }


        return $result->toArray();
    }

//## 路由：WxPayReport
//$router->get('wxPayReports', 'WxPayReportsController@index')->name('admin.wxPayReports');
//$router->get('wxPayReports/create', 'WxPayReportsController@create')->name('admin.wxPayReports.create');
//$router->get('wxPayReports/list', 'WxPayReportsController@list')->name('admin.wxPayReports.list');
//$router->post('wxPayReports/store', 'WxPayReportsController@store')->name('admin.wxPayReports.store');
//$router->get('wxPayReports/edit/{wxPayReport}', 'WxPayReportsController@edit')->name('admin.wxPayReports.edit');//隐式绑定
//$router->post('wxPayReports/update/{wxPayReport}', 'WxPayReportsController@update')->name('admin.wxPayReports.update');//隐式绑定
//$router->get('wxPayReports/destroy/{wxPayReport}', 'WxPayReportsController@destroy')->name('admin.wxPayReports.destroy');//隐式绑定
//$router->post('wxPayReports/destroyBat', 'WxPayReportsController@destroyBat')->name('admin.wxPayReports.destroyBat');

}
