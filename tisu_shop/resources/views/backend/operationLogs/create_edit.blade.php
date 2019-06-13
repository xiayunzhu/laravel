@extends('backend.layouts.app')

@section('title', $title = $operationLog->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>后台操作</a>
    <a href="{{ route('admin.operationLogs') }}">操作日志</a>
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
                    <label class="layui-form-label">UID</label>
                    <div class="layui-input-block">
                        <input type="text" name="uid" required lay-verify="required" placeholder="请输入UID" autocomplete="off" class="layui-input" value="{{ old('uid',$operationLog->uid) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">路径</label>
                    <div class="layui-input-block">
                        <input type="text" name="path" required lay-verify="required" placeholder="请输入路径" autocomplete="off" class="layui-input" value="{{ old('path',$operationLog->path) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">方法</label>
                    <div class="layui-input-block">
                        <input type="text" name="method" required lay-verify="required" placeholder="请输入方法" autocomplete="off" class="layui-input" value="{{ old('method',$operationLog->method) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">知识产权</label>
                    <div class="layui-input-block">
                        <input type="text" name="ip" required lay-verify="required" placeholder="请输入知识产权" autocomplete="off" class="layui-input" value="{{ old('ip',$operationLog->ip) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">SQL</label>
                    <div class="layui-input-block">
                        <input type="text" name="sql" required lay-verify="required" placeholder="请输入SQL" autocomplete="off" class="layui-input" value="{{ old('sql',$operationLog->sql) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">输入</label>
                    <div class="layui-input-block">
                        <input type="text" name="input" required lay-verify="required" placeholder="请输入输入" autocomplete="off" class="layui-input" value="{{ old('input',$operationLog->input) }}">
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
                let url = "{{$operationLog->id?route('admin.operationLogs.update',$operationLog->id):route('admin.operationLogs.store')}}";

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

                                window.location.href = "{{ url('admin/operationLogs/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush