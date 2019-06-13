@extends('backend.layouts.app')

@section('title', $title = $user->id ? '编辑卖家' : '添加卖家' )

@section('breadcrumb')
    <a>卖家管理</a>
    <a href="{{ route('admin.sellers') }}">卖家列表</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;">
        <div class="layui-form">
            {{--防跨越--}}
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>手机号</label>
                <div class="layui-input-inline">
                    <input type="text" name="phone" maxlength="20" placeholder="请输入手机号"
                           autocomplete="off" class="layui-input" value="{{ old('phone',$user->phone) }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1"
                           @if(old('status',$user->status??\App\Models\User::STATUS_Y) ==1) checked="checked" @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="radio" name="sex" value="1" title="男"
                           @if(old('sex',$user->sex) == 1) checked="checked" @endif>
                    <input type="radio" name="sex" value="0" title="女"
                           @if(old('sex',$user->sex) == 0) checked="checked" @endif>
                    {{--<input type="radio" name="sex" value="" title="中性" disabled>--}}
                </div>
            </div>


            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formUser">立即提交</button>
                    {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
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
            form.on('submit(formUser)', function (data) {
                let url = "{{$user->id?route('admin.sellers.update',$user->id):route('admin.sellers.store')}}";

                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: data.field,
                    dataType: "JSON",
                    success: function (result) {
                        let msg = result.message;

                        if (!result.success) {

                            layer.msg(msg);
                            // layer.open({
                            //     type: 1,
                            //     anim: 0,
                            //     title: msg,
                            //     area: ['50%', '70%'],
                            //     btn: ['关闭'],
                            //     content: JSON.stringify(result)
                            // });

                        } else {
                            // layer.msg(msg);
                            layer.alert(msg, function (index) {
                                layer.close(index);

                                let url = "{{route('admin.sellers')}}";
                                window.open(url, '_self');

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush