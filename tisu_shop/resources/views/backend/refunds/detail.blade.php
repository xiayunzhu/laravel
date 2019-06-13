@extends('backend.layouts.app')

@section('title', $title = $refund->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>售后管理</a>
    <a href="{{ route('admin.refunds') }}">售后订单</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <link rel="stylesheet" href="{{asset('css/progress.css')}}">

    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md10 layui-col-md-offset1 layui-col-lg10 layui-col-lg-offset1 site-block">
            <div class="layui-form">
                    <div class="layui-card">
                        <div class="layui-card-header"><b>售后进度</b></div>
                        <div class="layui-card-body">
                            <div class="eStep-warp" id="step1">
                            </div>
                        </div>
                    </div>
                <div class="layui-border-box" style="margin-top: 50px">
                </div>
                <blockquote class="layui-elem-quote">原订单信息</blockquote>
                <div class="layui-border-box">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td><div><b>商品</b></div></td>
                                <td><div><b>订单编号</b></div></td>
                                <td><div><b>卖家</b></div></td>
                                <td><div><b>买家</b></div></td>
                                <td><div><b>总金额</b></div></td>
                                <td align="center" class=""><div><b>总件数</b></div></td>
                            </tr>
                            <tr data-index="1" class="">
                                <td>
                                    <div><img src="{{storage_image_url($refund->order_item->image_url)}}"> </div>
                                    <div>
                                        {{$refund->order_item->spec_code}}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <p><label>主订单编号：</label>{{$refund->order_no}}</p>
                                        <p><label>子订单编号：</label>{{$refund->item_no}}</p>
                                    </div>
                                </td>
                                <td align="center">
                                    <div>
                                        <p>{{$refund->order->shop_nick}}</p>
                                        <p style="color: #aaaaaa">店铺ID：{{$refund->shop_id}}</p>
                                    </div>
                                </td>
                                <td align="center">
                                    <div>
                                        <p>{{$refund->buyer->buyer}}</p>
                                    </div>
                                </td>
                                <td style="height: 86px;" align="center">
                                    <div>
                                        <p>{{$refund->order_item->payment}}</p>
                                    </div>
                                </td>
                                <td data-field="publish_status" data-key="1-0-12" align="center">
                                    <div>
                                        <p>{{$refund->order_item->num}}</p>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">售后基本信息</blockquote>
                <div class="layui-border-box">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td><div><b>退款编号</b></div></td>
                                <td><div><b>退款金额</b></div></td>
                                <td><div><b>处理方式</b></div></td>
                                <td><div><b>申请时间</b></div></td>
                                <td><div><b>退款原因</b></div></td>
                                <td><div><b>买家留言</b></div></td>
                            </tr>
                            <tr data-index="1" class="">
                                <td>
                                    <div>
                                        {{$refund->refund_no}}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{$refund->back_money}}
                                    </div>
                                </td>
                                <td align="center">
                                    <div>
                                        {{\App\Models\Refund::$refundWayMap[$refund->refund_way]}}
                                    </div>
                                </td>
                                <td align="center">
                                    <div>
                                        <p>{{$refund->created_at}}</p>
                                    </div>
                                </td>
                                <td align="center">
                                    <div>
                                        {{\App\Models\Refund::$refundReasonMap[$refund->refund_reason]}}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <p>{{$refund->remark}}</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    @if(!empty($refund->image_urls))
                                        @foreach($refund->image_urls as $val=>$label)
                                            <a href="{{storage_image_url($label)}}"><img style="margin-left: 50px;" width="80px" height="80px" src="{{storage_image_url($label)}}"></a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <fieldset class="layui-elem-field site-demo-button" style="margin-top: 30px;">
                    <div style="margin: 10px;margin-left: 45%">
                        <button class="layui-btn layui-btn-normal  @if($refund->refund_progress != \App\Models\Refund::REFUND_PROGRESS_SHOP) layui-btn-disabled @endif"
                        @if($refund->refund_progress != \App\Models\Refund::REFUND_PROGRESS_SHOP) disabled @endif  onclick="operation('agree')">同意</button>
                        <button class="layui-btn layui-btn-primary @if($refund->refund_progress != \App\Models\Refund::REFUND_PROGRESS_SHOP) layui-btn-disabled @endif"
                        @if($refund->refund_progress != \App\Models\Refund::REFUND_PROGRESS_SHOP) disabled @endif onclick="operation('refuse')">拒绝</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>


    <style>
        .layui-elem-quote {
            border-left: 5px solid #393d49;
            background-color: #fff;
            border-bottom: 1px solid #cccccc;
            margin-top: 50px;
        }

        .layui-table-cell {
            margin: 0 auto;
            text-align: center;
        }

        .layui-table td {
            /*border-width: 2px !important;*/
            text-align: center;
        }
        #step1{
            margin-top: 20px;
            height: 50px;
        }
    </style>
@endsection
@push('scripts')
    <script src="{{asset('js/progress/progress.js')}}"></script>

    <script>

        $(function() {

            // 流程进度
            let flowNow = "{{$refund->refund_progress}}";

            console.log(flowNow);
            let stepArr = [{
                text: "{{\App\Models\Refund::$refundProgressMap[\App\Models\Refund::REFUND_PROGRESS_APPLYING]}}"
            }, {
                text: "{{\App\Models\Refund::$refundProgressMap[\App\Models\Refund::REFUND_PROGRESS_SHOP]}}"
            }, {
                text: "{{\App\Models\Refund::$refundProgressMap[\App\Models\Refund::REFUND_PROGRESS_AFTER_SALE]}}"
            }, {
                text: "{{\App\Models\Refund::$refundProgressMap[\App\Models\Refund::REFUND_PROGRESS_SUCCESS]}}"
            }];
            let flag = 0;


            switch (flowNow) {
                case ("{{\App\Models\Refund::REFUND_PROGRESS_APPLYING}}"):
                    flag = 0;
                    break;
                case ("{{\App\Models\Refund::REFUND_PROGRESS_SHOP}}"):
                    flag = 1;
                    break;
                case ("{{\App\Models\Refund::REFUND_PROGRESS_AFTER_SALE}}"):
                    flag = 2;
                    break;
                case ("{{\App\Models\Refund::REFUND_PROGRESS_SUCCESS}}"):
                    flag = 3;
                    break;
            }
            // 进度流程控制
            $('#step1').loadStep({
                steps: stepArr
            });
            $('#step1').setStep(flag);


        })
        function operation(handle){
            let url = "{{route('admin.refunds.operate',$refund->id)}}";
            let _token = "{{csrf_token()}}";
            if (handle == 'agree'){
                 status = "{{\App\Models\Refund::REFUND_REFUND}}";
                 refund_progress = "{{\App\Models\Refund::REFUND_PROGRESS_AFTER_SALE}}";
            } else{
                 status = "{{\App\Models\Refund::REFUND_REFUSE}}";
                 refund_progress = "{{\App\Models\Refund::REFUND_PROGRESS_SUCCESS}}";
            }

            $.ajax({
                type: 'POST',
                url: url,//发送请求
                data: {status: status,refund_progress: refund_progress, _token: _token},
                dataType: "JSON",
                success: function (result) {
                    let msg = result.message;

                    if (!result.success) {
                        layer.msg(msg);

                    } else {
                        layer.alert(msg, function (index) {
                            layer.close(index);
                            window.location.href = "{{ url('admin/refunds/detail') }}/" + result.model.id;
                        });

                    }

                }
            });
        }
    </script>
@endpush