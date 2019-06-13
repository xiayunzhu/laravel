@extends('backend.layouts.app')

@section('title', $title = $loginLog->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>其他功能</a>
    <a href="{{ route('admin.loginLogs') }}">日志</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">登录地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="address" required lay-verify="required" placeholder="请输入登录地址"
                               autocomplete="off" class="layui-input" value="{{ old('address',$loginLog->address) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">浏览器</label>
                    <div class="layui-input-block">
                        <input type="text" name="browser" required lay-verify="required" placeholder="请输入浏览器"
                               autocomplete="off" class="layui-input" value="{{ old('browser',$loginLog->browser) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">设备名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="device" required lay-verify="required" placeholder="请输入设备名称"
                               autocomplete="off" class="layui-input" value="{{ old('device',$loginLog->device) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">设备类型</label>
                    <div class="layui-input-block">
                        <input type="text" name="device_type" required lay-verify="required" placeholder="请输入设备类型"
                               autocomplete="off" class="layui-input"
                               value="{{ old('device_type',$loginLog->device_type) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">登录IP</label>
                    <div class="layui-input-block">
                        <input type="text" name="ip" required lay-verify="required" placeholder="请输入登录IP"
                               autocomplete="off" class="layui-input" value="{{ old('ip',$loginLog->ip) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">语言</label>
                    <div class="layui-input-block">
                        <input type="text" name="language" required lay-verify="required" placeholder="请输入语言"
                               autocomplete="off" class="layui-input" value="{{ old('language',$loginLog->language) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">登录时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="login_time" required lay-verify="required" placeholder="请输入登录时间"
                               autocomplete="off" class="layui-input"
                               value="{{ old('login_time',$loginLog->login_time) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">操作系统</label>
                    <div class="layui-input-block">
                        <input type="text" name="platform" required lay-verify="required" placeholder="请输入操作系统"
                               autocomplete="off" class="layui-input" value="{{ old('platform',$loginLog->platform) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_id" required lay-verify="required" placeholder="请输入用户ID"
                               autocomplete="off" class="layui-input" value="{{ old('user_id',$loginLog->user_id) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户昵称</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_name" required lay-verify="required" placeholder="请输入用户昵称"
                               autocomplete="off" class="layui-input"
                               value="{{ old('user_name',$loginLog->user_name) }}">
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
                let url = "{{$loginLog->id?route('admin.loginLogs.update',$loginLog->id):route('admin.loginLogs.store')}}";

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