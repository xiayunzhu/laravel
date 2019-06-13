@extends('backend.layouts.app')

@section('title', $title = $order->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>订单信息</a>
    <a href="{{ route('admin.orders') }}">订单详情</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">主订单编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_no" required lay-verify="required" placeholder="请输入主订单编号"
                               autocomplete="off" class="layui-input" value="{{ old('order_no',$order->order_no) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_name" required lay-verify="required" placeholder="请输入店铺名称"
                               autocomplete="off" class="layui-input" value="{{ old('shop_name',$order->shop_name) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺昵称</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_nick" required lay-verify="required" placeholder="请输入店铺昵称"
                               autocomplete="off" class="layui-input" value="{{ old('shop_nick',$order->shop_nick) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">来源</label>
                    <div class="layui-input-block">
                        <input type="text" name="source" required lay-verify="required" placeholder="请输入来源"
                               autocomplete="off" class="layui-input" value="{{ old('source',$order->source) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单总金额</label>
                    <div class="layui-input-block">
                        <input type="text" name="total_fee" required lay-verify="required" placeholder="请输入订单总金额"
                               autocomplete="off" class="layui-input" value="{{ old('total_fee',$order->total_fee) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">实际支付金额</label>
                    <div class="layui-input-block">
                        <input type="text" name="paid_fee" required lay-verify="required" placeholder="请输入实际支付金额"
                               autocomplete="off" class="layui-input" value="{{ old('paid_fee',$order->paid_fee) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">优惠金额</label>
                    <div class="layui-input-block">
                        <input type="text" name="discount_fee" required lay-verify="required" placeholder="请输入优惠金额"
                               autocomplete="off" class="layui-input"
                               value="{{ old('discount_fee',$order->discount_fee) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮费</label>
                    <div class="layui-input-block">
                        <input type="text" name="post_fee" required lay-verify="required" placeholder="请输入邮费"
                               autocomplete="off" class="layui-input" value="{{ old('post_fee',$order->post_fee) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">服务费</label>
                    <div class="layui-input-block">
                        <input type="text" name="service_fee" required lay-verify="required" placeholder="请输入服务费"
                               autocomplete="off" class="layui-input"
                               value="{{ old('service_fee',$order->service_fee) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">付款状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="pay_status" required lay-verify="required" placeholder="请输入付款状态"
                               autocomplete="off" class="layui-input"
                               value="{{ old('pay_status',$order->pay_status) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">付款时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="pay_time" required lay-verify="required" placeholder="请输入付款时间"
                               autocomplete="off" class="layui-input" value="{{ old('pay_time',$order->pay_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">快递费用</label>
                    <div class="layui-input-block">
                        <input type="text" name="express_price" required lay-verify="required" placeholder="请输入快递费用"
                               autocomplete="off" class="layui-input"
                               value="{{ old('express_price',$order->express_price) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">快递公司</label>
                    <div class="layui-input-block">
                        <input type="text" name="express_company" required lay-verify="required" placeholder="请输入快递公司"
                               autocomplete="off" class="layui-input"
                               value="{{ old('express_company',$order->express_company) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">快递单号</label>
                    <div class="layui-input-block">
                        <input type="text" name="express_no" required lay-verify="required" placeholder="请输入快递单号"
                               autocomplete="off" class="layui-input"
                               value="{{ old('express_no',$order->express_no) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">发货状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="send_status" required lay-verify="required"
                               placeholder="请输入发货状态-0: 待发货,1: 已发货" autocomplete="off" class="layui-input"
                               value="{{ old('send_status',$order->send_status) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">发货时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="send_time" required lay-verify="required" placeholder="请输入发货时间"
                               autocomplete="off" class="layui-input" value="{{ old('send_time',$order->send_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">收货状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="receipt_status" required lay-verify="required"
                               placeholder="请输入收货状态-0: 待收获 1: 已收货" autocomplete="off" class="layui-input"
                               value="{{ old('receipt_status',$order->receipt_status) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">收货时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="receipt_time" required lay-verify="required" placeholder="请输入收货时间"
                               autocomplete="off" class="layui-input"
                               value="{{ old('receipt_time',$order->receipt_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">退款状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="refund_status" required lay-verify="required"
                               placeholder="请输入退款状态-0: 无退款，1: 有退款" autocomplete="off" class="layui-input"
                               value="{{ old('refund_status',$order->refund_status) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_status" required lay-verify="required"
                               placeholder="请输入订单状态"
                               autocomplete="off" class="layui-input"
                               value="{{ old('order_status',$order->order_status) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">订单类型</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_type" required lay-verify="required"
                               placeholder="请输入订单类型 - 0: 普通订单" autocomplete="off" class="layui-input"
                               value="{{ old('order_type',$order->order_type) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关闭类型</label>
                    <div class="layui-input-block">
                        <input type="text" name="close_type" required lay-verify="required"
                               placeholder="请输入订单类型"
                               autocomplete="off" class="layui-input"
                               value="{{ old('close_type',$order->close_type) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关闭时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="close_time" required lay-verify="required" placeholder="请输入订单关闭时间"
                               autocomplete="off" class="layui-input"
                               value="{{ old('close_time',$order->close_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">创建时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="create_time" required lay-verify="required" placeholder="请输入创建时间"
                               autocomplete="off" class="layui-input"
                               value="{{ old('create_time',$order->create_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">更新时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="update_time" required lay-verify="required" placeholder="请输入更新时间"
                               autocomplete="off" class="layui-input"
                               value="{{ old('update_time',$order->update_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">买家备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="buyer_msg" required lay-verify="required" placeholder="请输入买家备注"
                               autocomplete="off" class="layui-input" value="{{ old('buyer_msg',$order->buyer_msg) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卖家备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="seller_msg" required lay-verify="required" placeholder="请输入卖家备注"
                               autocomplete="off" class="layui-input"
                               value="{{ old('seller_msg',$order->seller_msg) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">买家昵称</label>
                    <div class="layui-input-block">
                        <input type="text" name="buyer" required lay-verify="required" placeholder="请输入买家昵称"
                               autocomplete="off" class="layui-input" value="{{ old('buyer',$order->buyer) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">买家ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="buyer_id" required lay-verify="required" placeholder="请输入买家ID"
                               autocomplete="off" class="layui-input" value="{{ old('buyer_id',$order->buyer_id) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入店铺ID"
                               autocomplete="off" class="layui-input" value="{{ old('shop_id',$order->shop_id) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                        {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                    </div>
                </div>
            </div>
        </div>

    </div>
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