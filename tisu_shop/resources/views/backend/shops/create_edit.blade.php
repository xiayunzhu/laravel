@extends('backend.layouts.app')

@section('title', $title = $shop->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>店铺资料</a>
    <a href="{{ route('admin.shops') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <fieldset class="layui-elem-field">
                    <legend>店铺资料</legend>
                    <div class="layui-field-box">

                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺代号<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_code" lay-verify="pass" required placeholder="请输入 店铺代号"
                                       autocomplete="off" class="layui-input" value="{{ old('shop_code',$shop->shop_code) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺昵称<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_nick" required lay-verify="required" placeholder="请输入店铺昵称"
                                       autocomplete="off" class="layui-input" value="{{ old('shop_nick',$shop->shop_nick) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺名称<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_name" required lay-verify="required" placeholder="请输入店铺名称"
                                       autocomplete="off" class="layui-input" value="{{ old('shop_name',$shop->shop_name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺图标<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <div class="layui-upload">
                                        <button type="button" class="layui-btn layui-btn-normal layui-btn-sm"
                                                id="upload_btn">上传图片
                                        </button>
                                    <div class="layui-upload-list">
                                        <input type="hidden" name="icon_url" id="form_avatar"
                                               value="{{ old('icon_url',$shop->icon_url) }}"/>
                                        <img src="{{ storage_image_url($shop->icon_url) }}" id="image_avatar"
                                             class="img-rounded"
                                             width="200px"
                                             height="200px" alt="">
                                        <p id="errorText"></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺简介<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="introduction" required lay-verify="required" placeholder="请输入店铺简介"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('introduction',$shop->introduction) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">

                                <div class="layui-inline">
                                    <label class="layui-form-label">卖家用户ID<i style="color: red">*</i></label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="user_id" id="userIdInput" disabled required lay-verify="required" placeholder="归属的用户ID"
                                               autocomplete="off" class="layui-input" value="{{ old('user_id',$shop->user_id) }}">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">
                                        <a id="chooseUser">选择卖家
                                            <i class="layui-icon layui-icon-add-1"></i></a></label>
                                    <div class="layui-input-inline">

                                        <input type="text" id="userNameInput" required
                                               lay-verify="required"
                                               placeholder="请选择归属卖家"
                                               autocomplete="off" class="layui-input"
                                               value="{{old('username',$shop->username )}}" disabled>

                                    </div>
                                </div>




                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                                {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        <script type="text/html" id="bartblShop">
            <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="choose">选择卖家</a></script>
    </div>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use(['form','upload'], function () {
            let form = layui.form;
            var $ = layui.jquery
                , upload = layui.upload;
            let _token = "{{csrf_token()}}";

            // 监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$shop->id?route('admin.shops.update',$shop->id):route('admin.shops.store')}}";
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

                            layer.alert(msg, function (index) {
                                layer.close(index);

                                window.history.go(-1);


                            });

                        }

                    }
                });
            });

            // 图片上传
            let upload_url = '{{ route('uploader') }}?file_type=image&folder=avatar&_token=' + _token;
            let uploadInst = upload.render({
                    elem: '#upload_btn'
                    , url: upload_url
                    , size: 5120 //限制文件大小，单位 KB
                    , field: 'upload_file'//设定文件域的字段名 默认file
                    , accept: 'image' //普通文件
                    , acceptMime: 'image/*' //（只显示图片文件）
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#image_avatar').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        console.log(res);
                        //如果上传失败
                        if (res.code > 0) {
                            return layer.msg('上传失败');
                        }
                        //上传成功
                        $("#form_avatar").val(res.path);
                        $("#form_avatar").attr(res.path);
                    }
                    , error: function () {
                        //演示失败状态，并实现重传
                        var demoText = $('#errorText');
                        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        demoText.find('.demo-reload').on('click', function () {
                            uploadInst.upload();
                        });
                    }
            });

            // 选卖家用户
            $("#chooseUser").on('click', function () {

                let content = '<div style="padding: 10px;"><div class="layui-form" id="queryForm">' +
                    '<div class="layui-form-item">' +
                    '<div class="layui-inline"> <div class="layui-input-inline">' +
                    '<input type="text" class="layui-input" name="nickname" placeholder="名称"> ' +
                    '</div>' +
                    '<div class="layui-inline"> <div class="layui-input-inline">' +
                    ' <button class="layui-btn" id="searchArtist" lay-submit lay-filter="formSearchArtist">查询</button> ' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div><table id="chooseUserTable" lay-filter="tblArtistTable"></table></div></div>';

                layer.open({
                    type: 1,
                    area: ['80%', '60%'],
                    content: content,
                    success: function (layero, index) {
                        let table = layui.table;
                        table.render({
                            elem: '#chooseUserTable'
                            , url: "{{url('admin/sellers/list')}}"  //数据接口
                            , title: "卖家用户列表"
                            , page: true //开启分页
                            , cols: [[ //表头
                                {field: 'id', title: 'ID', width: 50}
                                , {field: 'username', title: '用户名', width: 200}
                                , {field: 'name', title: '昵称', width: 120}
                                , {field: 'phone', title: '手机号码', width: 120}
                                , {field: 'email', title: '邮箱', width: 120}
                                , {
                                    field: 'sex', title: '性别', width: 80, sort: true
                                    , templet: function (d) {
                                        let txt = '<span style="color: #c00;">未知<i class="layui-icon layui-icon-close" style="font-size: 20px; color: #c00;"></i></span>';
                                        if (d.sex === 1) {
                                            txt = '<span style="color: #1E9FFF;">男<i class="layui-icon layui-icon-male" style="font-size: 20px; color: #5FB878;"></i></span>';
                                        } else if (d.sex === 0) {
                                            txt = '<span style="color: #FF5722;">女<i class="layui-icon layui-icon-male" style="font-size: 20px; color: #FF5722;"></i></span>';
                                        }
                                        return txt;
                                    }
                                }
                                , {fixed: 'right', title: '操作项', width: 100, align: 'center', toolbar: '#bartblShop'}
                            ]]
                            , parseData: function (res) {
                                return {
                                    "code": res.success ? 0 : 1, //解析接口状态
                                    "msg": res.message, //解析提示文本
                                    "count": res.model.total, //解析数据长度
                                    "data": res.model.data //解析数据列表
                                };
                            }
                        })

                        //监听行工具事件
                        table.on('tool(tblArtistTable)', function (obj) { //注：tool 是工具条事件名，tblFilter 是 table 原始容器的属性 lay-filter="对应的值"
                            let data = obj.data //获得当前行数据
                                , layEvent = obj.event; //获得 lay-event 对应的值
                            if (layEvent === 'choose') {
                                $("#userIdInput").val(data.id);
                                $("#userNameInput").val(data.name);


                                layer.close(index);
                            }
                        });

                        //监听查询按钮
                        form.on('submit(formSearchArtist)', function (data) {
                            let queryFields = data.field;
                            //表格数据重载
                            table.reload('chooseArtistTable', {
                                where: {
                                    queryFields
                                },
                                page: {
                                    curr: 1
                                }
                            });

                        });
                    }
                })
            })

            //自定义验证规则
            form.verify({
                pass : [
                    /^[\S]{0,10}$/,
                    '店铺代号必须10位数字以内且不能出现空格'
                ]
            });
        });
    </script>
@endpush