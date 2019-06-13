@extends('backend.layouts.app')

@section('title', $title = $category->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>分类管理</a>
    <a href="{{ route('admin.categories') }}">分类列表</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@php
    $parentCategories=App\Handlers\CategoryHandler::parentCategories();
@endphp
@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md6 layui-col-md-offset3">
            <div style="text-align: right">
                @if( $category->id)
                    <button type="reset" class="layui-btn layui-btn-normal" id="reset">新增</button>
                @endif
            </div>

            <fieldset class="layui-elem-field">
                <legend>分类 - {{$title}}</legend>
                <div class="layui-field-box">
                    <div class="layui-form">
                        {{--防跨越--}}
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label">名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" required lay-verify="required" placeholder="请输入名称"
                                       autocomplete="off" class="layui-input" value="{{ old('name',$category->name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">代号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" placeholder="请输入代号"
                                       autocomplete="off" class="layui-input" value="{{ old('code',$category->code) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上级分类</label>
                            <div class="layui-input-block">
                                <select name="parent_id" required lay-verify="required" lay-search>
                                    <option value="0">顶级类目</option>
                                    @if(!empty($parentCategories))
                                        @foreach($parentCategories as $val => $label)
                                            <option value="{{$val}}"
                                                    @if($val==old('parent_id',$category->parent_id)) selected @endif>{{$label}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">图片</label>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <div class="layui-upload-drag" id="test10">
                                        <i class="layui-icon"></i>
                                        <p>点击上传，或将文件拖拽到此处</p>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input type="hidden" name="image_url" id="image_url"
                                           value="{{old('image_url',$category->image_url) }}"/>
                                    <div class="layui-upload-drag">
                                        <img src="{{ $category->image_url?storage_url($category->image_url):'' }}"
                                             id="image_url_show"
                                             class="img-rounded"
                                             width="200px"
                                             height="200px" alt="">
                                    </div>

                                    <button class="layui-btn layui-btn-warm" id="delete_image_url"><i
                                                class="layui-icon layui-icon-delete"></i></button>

                                </div>

                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">

                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-inline">
                                <input type="number" name="sort" required lay-verify="required"
                                       placeholder="请输入排序（ 数字越小越靠前）"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('sort',!is_null($category->sort)?$category->sort:100) }}">
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
            </fieldset>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use('form', function () {
            let form = layui.form;
            var $ = layui.jquery;

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$category->id?route('admin.categories.update',$category->id):route('admin.categories.store')}}";

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

            $("#reset").on('click', function () {
                window.location.href = "{{ route('admin.categories.create') }}";
            })
        });

        //文档： https://www.layui.com/doc/modules/upload.html
        layui.use('upload', function () {
            var $ = layui.jquery
                , upload = layui.upload;

            let _token = "{{csrf_token()}}";

            let upload_url = '{{ route('uploader') }}?file_type=image&folder=category&_token=' + _token;
            //拖拽上传
            upload.render({
                elem: '#test10'
                , url: upload_url
                , size: 5120 //限制文件大小，单位 KB
                // ,auto: false ////选完文件后不自动上传
                , field: 'upload_file'//设定文件域的字段名 默认file
                , accept: 'image' //普通文件
                , acceptMime: 'image/*' //（只显示图片文件）
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $("#image_url").val(res.path);
                    $("#image_url").attr(res.path);
                    $("#image_url_show").attr('src', res.url);

                }
            });

            //删除图片
            $("#delete_image_url").on('click', function () {
                layer.confirm('确定删除图片？', function () {
                    $("#image_url").val('');
                    $("#image_url").attr('');
                    $("#image_url_show").attr('src', '');
                    layer.msg('已删除');
                })
            })
        });
    </script>
@endpush