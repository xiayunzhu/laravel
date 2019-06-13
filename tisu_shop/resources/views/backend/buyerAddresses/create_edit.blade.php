@extends('backend.layouts.app')

@section('title', $title = $buyerAddress->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.buyerAddresses') }}">资料</a>
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
                    <label class="layui-form-label">接受者</label>
                    <div class="layui-input-block">
                        <input type="text" name="receiver" required lay-verify="required" placeholder="请输入收货人" autocomplete="off" class="layui-input" value="{{ old('receiver',$buyerAddress->receiver) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">可移动的</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" required lay-verify="required" placeholder="请输入手机" autocomplete="off" class="layui-input" value="{{ old('mobile',$buyerAddress->mobile) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">电话</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" required lay-verify="required" placeholder="请输入电话" autocomplete="off" class="layui-input" value="{{ old('phone',$buyerAddress->phone) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">省份</label>
                    <div class="layui-input-block">
                        <input type="text" name="province" required lay-verify="required" placeholder="请输入省份" autocomplete="off" class="layui-input" value="{{ old('province',$buyerAddress->province) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">城市</label>
                    <div class="layui-input-block">
                        <input type="text" name="city" required lay-verify="required" placeholder="请输入城市" autocomplete="off" class="layui-input" value="{{ old('city',$buyerAddress->city) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">区</label>
                    <div class="layui-input-block">
                        <input type="text" name="district" required lay-verify="required" placeholder="请输入区" autocomplete="off" class="layui-input" value="{{ old('district',$buyerAddress->district) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">细节</label>
                    <div class="layui-input-block">
                        <input type="text" name="detail" required lay-verify="required" placeholder="请输入细节" autocomplete="off" class="layui-input" value="{{ old('detail',$buyerAddress->detail) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">齐普码</label>
                    <div class="layui-input-block">
                        <input type="text" name="zip_code" required lay-verify="required" placeholder="请输入邮编" autocomplete="off" class="layui-input" value="{{ old('zip_code',$buyerAddress->zip_code) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">缺省默认值</label>
                    <div class="layui-input-block">
                        <input type="text" name="is_default" required lay-verify="required" placeholder="是否默认" autocomplete="off" class="layui-input" value="{{ old('is_default',$buyerAddress->is_default) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">布埃尔齐德</label>
                    <div class="layui-input-block">
                        <input type="text" name="buyer_id" required lay-verify="required" placeholder="请输入用户ID" autocomplete="off" class="layui-input" value="{{ old('buyer_id',$buyerAddress->buyer_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">购物狂</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入商铺ID" autocomplete="off" class="layui-input" value="{{ old('shop_id',$buyerAddress->shop_id) }}">
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
                let url = "{{$buyerAddress->id?route('admin.buyerAddresses.update',$buyerAddress->id):route('admin.buyerAddresses.store')}}";

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

                                window.location.href = "{{ url('admin/buyerAddresses/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush