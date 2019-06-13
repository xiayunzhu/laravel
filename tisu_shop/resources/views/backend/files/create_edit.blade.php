@extends('backend.layouts.app')

@section('title', $title = $file->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.files') }}">文件管理</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                             <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-block">
                        <input type="text" name="type" required lay-verify="required" placeholder="请输入 type" autocomplete="off" class="layui-input" value="{{ old('type',$file->type) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">路径</label>
                    <div class="layui-input-block">
                        <input type="text" name="path" required lay-verify="required" placeholder="请输入 path" autocomplete="off" class="layui-input" value="{{ old('path',$file->path) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">MIME-型</label>
                    <div class="layui-input-block">
                        <input type="text" name="mime_type" required lay-verify="required" placeholder="请输入 mime_type" autocomplete="off" class="layui-input" value="{{ old('mime_type',$file->mime_type) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">MD5</label>
                    <div class="layui-input-block">
                        <input type="text" name="md5" required lay-verify="required" placeholder="请输入 md5" autocomplete="off" class="layui-input" value="{{ old('md5',$file->md5) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" required lay-verify="required" placeholder="请输入 title" autocomplete="off" class="layui-input" value="{{ old('title',$file->title) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">文件夹</label>
                    <div class="layui-input-block">
                        <input type="text" name="folder" required lay-verify="required" placeholder="请输入 folder" autocomplete="off" class="layui-input" value="{{ old('folder',$file->folder) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">对象编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="object_id" required lay-verify="required" placeholder="请输入 object_id" autocomplete="off" class="layui-input" value="{{ old('object_id',$file->object_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">大小</label>
                    <div class="layui-input-block">
                        <input type="text" name="size" required lay-verify="required" placeholder="请输入 size" autocomplete="off" class="layui-input" value="{{ old('size',$file->size) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">宽度</label>
                    <div class="layui-input-block">
                        <input type="text" name="width" required lay-verify="required" placeholder="请输入 width" autocomplete="off" class="layui-input" value="{{ old('width',$file->width) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">高度</label>
                    <div class="layui-input-block">
                        <input type="text" name="height" required lay-verify="required" placeholder="请输入 height" autocomplete="off" class="layui-input" value="{{ old('height',$file->height) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">下载</label>
                    <div class="layui-input-block">
                        <input type="text" name="downloads" required lay-verify="required" placeholder="请输入 downloads" autocomplete="off" class="layui-input" value="{{ old('downloads',$file->downloads) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">公众的</label>
                    <div class="layui-input-block">
                        <input type="text" name="public" required lay-verify="required" placeholder="请输入 public" autocomplete="off" class="layui-input" value="{{ old('public',$file->public) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">编辑</label>
                    <div class="layui-input-block">
                        <input type="text" name="editor" required lay-verify="required" placeholder="请输入 editor" autocomplete="off" class="layui-input" value="{{ old('editor',$file->editor) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">地位</label>
                    <div class="layui-input-block">
                        <input type="text" name="status" required lay-verify="required" placeholder="请输入 status" autocomplete="off" class="layui-input" value="{{ old('status',$file->status) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">创世记</label>
                    <div class="layui-input-block">
                        <input type="text" name="created_op" required lay-verify="required" placeholder="请输入 created_op" autocomplete="off" class="layui-input" value="{{ old('created_op',$file->created_op) }}">
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
                let url = "{{$file->id?route('admin.files.update',$file->id):route('admin.files.store')}}";

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

                                let url = "{{route('admin.files')}}";
                                window.open(url, '_self');

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush