@extends('backend.layouts.app')

@section('title', $title = $order->id ? '详情' : '--' )

@section('breadcrumb')
    <a>订单信息</a>
    <a href="{{ route('admin.orders') }}">订单</a>
    <a><cite>{{$title}}</cite></a>

@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md10 layui-col-md-offset1 layui-col-lg10 layui-col-lg-offset1 site-block">

            <div class="layui-form">
                <blockquote class="layui-elem-quote">基本信息</blockquote>
                <div class="layui-border-box">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td>
                                    <div><b>订单编号</b></div>
                                </td>
                                <td>
                                    <div><b>买家</b></div>
                                </td>
                                <td>
                                    <div><b>订单金额</b></div>
                                </td>
                                <td align="center" class="">
                                    <div><b>配送方式</b></div>
                                </td>
                                <td>
                                    <div><b>交易状态</b></div>
                                </td>
                            </tr>
                            <tr data-index="1" class="">
                                <td>
                                    <div>{{$order->order_no}}</div>
                                </td>
                                <td align="center">
                                    <div>
                                        <p>{{$order->buyer}}</p>
                                        <p style="color: #aaaaaa">用户ID：{{$order->buyer_id}}</p>
                                    </div>
                                </td>
                                <td style="height: 86px;" align="center">
                                    <div>
                                        <p><label>订单总额：</label>{{$order->total_fee}}</p>
                                        <p><label>运费总额：</label>{{$order->post_fee}}</p>
                                        <p><label>实付款总额：</label><i style="color: darkred">{{$order->paid_fee}}</i></p>
                                    </div>
                                </td>
                                <td data-field="sales_status" data-key="1-0-11" data-content="sold_out" class="">
                                    <div class="layui-table-cell laytable-cell-1-0-11">
                                        <span class="layui-badge layui-bg-blue">配送方式</span>
                                    </div>
                                </td>
                                <td data-field="publish_status" data-key="1-0-12" align="center">
                                    <div>
                                        <p><label>付款状态：</label>
                                            @if($order->pay_status !== '')
                                                @switch($order->pay_status)
                                                    @case(\App\Models\Order::PAY_STATUS_WAIT)
                                                    <span class="layui-badge layui-bg-gray">{{\App\Models\Order::$payStatusMap[\App\Models\Order::PAY_STATUS_WAIT]}}</span>
                                                    @break
                                                    @case(\App\Models\Order::PAY_STATUS_DONE)
                                                    <span class="layui-badge layui-bg-green">{{\App\Models\Order::$payStatusMap[\App\Models\Order::PAY_STATUS_DONE]}}</span>
                                                    @break
                                                @endswitch
                                            @endif
                                        </p>
                                        <p><label>发货状态：</label>
                                            @if($order->send_status !== '')
                                                @switch($order->send_status)
                                                    @case(\App\Models\Order::SEND_STATUS_WAIT)
                                                    <span class="layui-badge layui-bg-gray">{{\App\Models\Order::$sendStatusMap[\App\Models\Order::SEND_STATUS_WAIT]}}</span>
                                                    @break
                                                    @case(\App\Models\Order::SEND_STATUS_DONE)
                                                    <span class="layui-badge layui-bg-green">{{\App\Models\Order::$sendStatusMap[\App\Models\Order::SEND_STATUS_DONE]}}</span>
                                                    @break
                                                @endswitch
                                            @endif
                                        </p>
                                        <p><label>收货状态：</label>
                                            @if($order->receipt_status !== '')
                                                @switch($order->receipt_status)
                                                    @case(\App\Models\Order::RECEIPT_STATUS_WAIT)
                                                    <span class="layui-badge layui-bg-gray">{{\App\Models\Order::$receiptStatusMap[\App\Models\Order::RECEIPT_STATUS_WAIT]}}</span>
                                                    @break
                                                    @case(\App\Models\Order::RECEIPT_STATUS_DONE)
                                                    <span class="layui-badge layui-bg-green">{{\App\Models\Order::$receiptStatusMap[\App\Models\Order::RECEIPT_STATUS_DONE]}}</span>
                                                    @break
                                                @endswitch
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">商品信息</blockquote>
                <div class="layui-table-box">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td >
                                    <div><b>商品名称</b></div>
                                </td>
                                <td>
                                    <div><b>规格编码</b></div>
                                </td>
                                <td >
                                    <div><b>重量(Kg)</b></div>
                                </td>
                                <td>
                                    <div><b>单价</b></div>
                                </td>
                                <td align="center" class="">
                                    <div><b>购买数量</b></div>
                                </td>
                                <td>
                                    <div><b>商品总价</b></div>
                                </td>
                            </tr>
                            @if(!empty($order->order_items))
                                @foreach($order->order_items as $key=>$order_item)
                                    <tr data-index="2" class="">
                                        <td data-field="sales_initial" style="width: 400px;height: 90px;" class="">
                                            <div style="height: 90px">
                                                <div style="width: 20%;height: 90px;float: left;">
                                                    <img height="100%" width="100%"
                                                         src="{{storage_image_url($order_item['goods']['logo_image']['file_url'])}}">
                                                </div>
                                                <div style="width: 60%;float: left;margin-left: 10px;">
                                                    <p>{{$order_item->spec_code}}</p>
                                                    <p style="color: #aaaaaa;margin-top: 9px">{{$order_item->goods_name}}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{$order_item->goods_no}}</div>
                                        </td>
                                        <td style="height: 140px;"
                                            align="center">
                                            <div>{{$order_item->weight}}</div>
                                        </td>
                                        <td >
                                            <div >{{$order_item->goods_price}}</div>
                                        </td>
                                        <td >
                                            <div class="layui-table-cell laytable-cell-1-0-12">{{$order_item->num}}</div>
                                        </td>
                                        <td  align="center"    style="width: 150px">
                                            <div>{{$order_item->payment}}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="6">
                                    <div class="layui-table-cell laytable-cell-1-0-12">
                                        <div style="float: left">买家留言：{{$order->buyer_msg}}</div>
                                        <div style="float: right">总计金额：{{$order->paid_fee}}</div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">收货信息</blockquote>
                <div class=" layui-form layui-table-box">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td>
                                    <div><b>收货人</b></div>
                                </td>
                                <td>
                                    <div><b>收货电话</b></div>
                                </td>
                                <td>
                                    <div><b>收货地址</b></div>
                                </td>
                            </tr>

                            <tr data-index="2" class="">
                                <td>
                                    <div>{{$order->address->receiver}}</div>
                                </td>
                                <td>
                                    <div>{{$order->address->mobile}}</div>
                                </td>

                                <td >
                                    <div>{{$order->address->province.$order->address->city.$order->address->district.$order->address->detail}}</div>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">付款信息</blockquote>
                <div class="layui-table-box  ">

                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr data-index="0">
                                <td>
                                    <div><b>付款金额</b></div>
                                </td>
                                <td>
                                    <div><b>支付方式</b></div>
                                </td>
                                <td>
                                    <div><b>支付流水号</b></div>
                                </td>
                                <td>
                                    <div><b>付款状态</b></div>
                                </td>
                                <td>
                                    <div><b>付款时间</b></div>
                                </td>
                            </tr>

                            <tr data-index="2" class="">
                                <td>
                                    <div>{{$order->paid_fee}}</div>
                                </td>
                                <td>
                                    <div>15040219241</div>
                                </td>

                                <td>
                                    <div>售罄</div>
                                </td>

                                <td>
                                    <div>售罄</div>
                                </td>

                                <td>
                                    <div>售罄</div>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <blockquote class="layui-elem-quote">发货信息</blockquote>
                <div class="layui-table-box  ">
                    <div class="layui-table-body layui-table-main">
                        <table cellspacing="0" cellpadding="0" border="0" class="layui-table">
                            <tbody>
                            <tr>
                                <td>
                                    <div><b>物流公司</b></div>
                                </td>
                                <td>
                                    <div><b>物流单号</b></div>
                                </td>
                                <td>
                                    <div><b>发货状态</b></div>
                                </td>
                                <td>
                                    <div><b>发货时间</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>12</div>
                                </td>
                                <td>
                                    <div>15040219241</div>
                                </td>

                                <td>
                                    <div>售罄</div>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
            border-width: 2px !important;
            text-align: center;
        }
    </style>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use('form', function () {
            let form = layui.form;

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$order->id?route('admin.orders.update',$order->id):route('admin.orders.store')}}";

                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: data.field,
                    dataType: "JSON",
                    success: function (result) {
                        let msg = result.message;

                        if (!result.success) {
                            layer.msg(msg);

                        } else {
                            // layer.msg(msg);
                            layer.alert(msg, function (index) {
                                layer.close(index);

                                window.location.href = "{{ url('admin/categories/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush