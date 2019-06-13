@extends('backend.layouts.plain')
@section('title', $title = $uploadGroup->id ? '编辑' : '添加' )

@php

@endphp
@section('content')

    <div class="layui-col-md12">
            <div class="layui-field-box">
                <div class="layui-form">
                    {{--防跨越--}}
                    {{ csrf_field() }}

                    <div class="layui-form-item">
                        <label class="layui-form-label">分组名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="group_name" maxlength="64" required lay-verify="required"
                                   placeholder="请输入分组名称"
                                   autocomplete="off" class="layui-input" value="{{ old('group_name',$uploadGroup->group_name) }}">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-inline">
                            <input type="number" name="sort" required lay-verify="required"
                                   placeholder="请输入排序（ 数字越小越靠前）"
                                   autocomplete="off" class="layui-input"
                                   value="{{ old('sort',!is_null($uploadGroup->sort)?$uploadGroup->sort:100) }}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            排序数字越小越靠前
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formCommit">保存</button>

                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection
@push('scripts')
    <script>
        let indexWindow = parent.layer.getFrameIndex(window.name); //获取窗口索引

        //Demo
        layui.use('form', function () {
            let form = layui.form;
            let $ = layui.jquery;
            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$uploadGroup->id?route('admin.uploadGroups.update',$uploadGroup->id):route('admin.uploadGroups.store')}}";
                console.log(data.field);
                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: data.field,
                    dataType: "JSON",
                    success: function (result) {
                        let msg = result.message;

                        if (!result.success) {
                            parent.layer.msg(msg);
                        } else {
                            let name = result.model.group_name;
                            let id = result.model.id;
                            layer.confirm(msg + '：' + name, function (index) {
                                layer.close(index);

                                parent.layer.close(indexWindow);
                                //返回值给
                                parent.window.location.reload();
                            });
                        }

                    }
                });


            });

            $("#reset").on('click', function () {
                window.location.href = "{{ route('admin.uploadGroups.create') }}";
            })
        });


    </script>
@endpush