@extends('backend.layouts.app')

@section('title', $title = $address->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.address') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection
@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">data1</label>
                    <div class="layui-input-block">
                        <input type="text" name="province" required lay-verify="required" placeholder="请输入data1" autocomplete="off" class="layui-input" value="{{ old('province',$address->province) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">data2</label>
                    <div class="layui-input-block">
                        <input type="text" name="city" required lay-verify="required" placeholder="请输入data2" autocomplete="off" class="layui-input" value="{{ old('city',$address->city) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">data3</label>
                    <div class="layui-input-block">
                        <input type="text" name="country" required lay-verify="required" placeholder="请输入data3" autocomplete="off" class="layui-input" value="{{ old('country',$address->country) }}">
                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
                let url = "{{$address->id?route('admin.address.update',$address->id):route('admin.address.store')}}";

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

                                {{--window.location.href = "{{ url('admin/categories/edit') }}/" + result.model.id;--}}
                                window.location.href = "{{ url('admin/address') }}";


                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush