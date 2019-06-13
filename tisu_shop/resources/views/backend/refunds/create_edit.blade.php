@extends('backend.layouts.app')

@section('title', $title = $refund->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.refunds') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
         <fieldset class="layui-elem-field">
            <legend>详情 - {{$title}}</legend>
            <div class="layui-field-box">

                <div class="layui-form">
                    {{--防跨越--}}
                    {{ csrf_field() }}
                                 <div class="layui-form-item">
                    <label class="layui-form-label">主订单编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="order_no" required lay-verify="required" placeholder="请输入主订单编号" autocomplete="off" class="layui-input" value="{{ old('order_no',$refund->order_no) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">子订单编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="item_no" required lay-verify="required" placeholder="请输入子订单编号" autocomplete="off" class="layui-input" value="{{ old('item_no',$refund->item_no) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款用户ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_id" required lay-verify="required" placeholder="请输入退款用户ID" autocomplete="off" class="layui-input" value="{{ old('user_id',$refund->user_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">商品ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_id" required lay-verify="required" placeholder="请输入商品ID" autocomplete="off" class="layui-input" value="{{ old('goods_id',$refund->goods_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">商品规格ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_spec_id" required lay-verify="required" placeholder="请输入商品规格ID" autocomplete="off" class="layui-input" value="{{ old('goods_spec_id',$refund->goods_spec_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款订单编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="refund_no" required lay-verify="required" placeholder="请输入退款订单编号" autocomplete="off" class="layui-input" value="{{ old('refund_no',$refund->refund_no) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">处理方式</label>
                    <div class="layui-input-block">
                        <input type="text" name="refund_way" required lay-verify="required" placeholder="请输入处理方式" autocomplete="off" class="layui-input" value="{{ old('refund_way',$refund->refund_way) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款原因</label>
                    <div class="layui-input-block">
                        <input type="text" name="refund_reason" required lay-verify="required" placeholder="请输入退款原因" autocomplete="off" class="layui-input" value="{{ old('refund_reason',$refund->refund_reason) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款金额</label>
                    <div class="layui-input-block">
                        <input type="text" name="back_money" required lay-verify="required" placeholder="请输入退款金额" autocomplete="off" class="layui-input" value="{{ old('back_money',$refund->back_money) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">手机号码</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" required lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input" value="{{ old('phone',$refund->phone) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="remark" required lay-verify="required" placeholder="请输入备注" autocomplete="off" class="layui-input" value="{{ old('remark',$refund->remark) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">图片地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="image_urls" required lay-verify="required" placeholder="请输入图片地址" autocomplete="off" class="layui-input" value="{{ old('image_urls',$refund->image_urls) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="status" required lay-verify="required" placeholder="请输入退款状态" autocomplete="off" class="layui-input" value="{{ old('status',$refund->status) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">退款进度</label>
                    <div class="layui-input-block">
                        <input type="text" name="refund_progress" required lay-verify="required" placeholder="请输入退款进度" autocomplete="off" class="layui-input" value="{{ old('refund_progress',$refund->refund_progress) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">到账时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="arrive_time" required lay-verify="required" placeholder="请输入到账时间" autocomplete="off" class="layui-input" value="{{ old('arrive_time',$refund->arrive_time) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">店铺ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入店铺ID" autocomplete="off" class="layui-input" value="{{ old('shop_id',$refund->shop_id) }}">
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
         </fieldset>
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
                let url = "{{$refund->id?route('admin.refunds.update',$refund->id):route('admin.refunds.store')}}";

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

                                window.location.href = "{{ url('admin/refunds/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush