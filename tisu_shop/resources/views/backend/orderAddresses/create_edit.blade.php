@extends('backend.layouts.app')

@section('title', $title = $orderAddress->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.orderAddresses') }}">资料</a>
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
                        <input type="text" name="order_no" required lay-verify="required" placeholder="请输入主订单编号" autocomplete="off" class="layui-input" value="{{ old('order_no',$orderAddress->order_no) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">收件人</label>
                    <div class="layui-input-block">
                        <input type="text" name="receiver" required lay-verify="required" placeholder="请输入收件人" autocomplete="off" class="layui-input" value="{{ old('receiver',$orderAddress->receiver) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">手机</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" required lay-verify="required" placeholder="请输入手机" autocomplete="off" class="layui-input" value="{{ old('phone',$orderAddress->phone) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">省</label>
                    <div class="layui-input-block">
                        <input type="text" name="province" required lay-verify="required" placeholder="请输入省" autocomplete="off" class="layui-input" value="{{ old('province',$orderAddress->province) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">市</label>
                    <div class="layui-input-block">
                        <input type="text" name="city" required lay-verify="required" placeholder="请输入市" autocomplete="off" class="layui-input" value="{{ old('city',$orderAddress->city) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">区</label>
                    <div class="layui-input-block">
                        <input type="text" name="district" required lay-verify="required" placeholder="请输入区" autocomplete="off" class="layui-input" value="{{ old('district',$orderAddress->district) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">详细地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="detail" required lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input" value="{{ old('detail',$orderAddress->detail) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">买家id</label>
                    <div class="layui-input-block">
                        <input type="text" name="buyer_id" required lay-verify="required" placeholder="请输入买家id" autocomplete="off" class="layui-input" value="{{ old('buyer_id',$orderAddress->buyer_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">创建时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="create_time" required lay-verify="required" placeholder="请输入创建时间" autocomplete="off" class="layui-input" value="{{ old('create_time',$orderAddress->create_time) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">店铺ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入店铺ID" autocomplete="off" class="layui-input" value="{{ old('shop_id',$orderAddress->shop_id) }}">
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
                let url = "{{$orderAddress->id?route('admin.orderAddresses.update',$orderAddress->id):route('admin.orderAddresses.store')}}";

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