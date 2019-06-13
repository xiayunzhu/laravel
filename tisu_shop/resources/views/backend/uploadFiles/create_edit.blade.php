@extends('backend.layouts.app')



@section('breadcrumb')
    <a>图片库管理</a>
    <a href="{{ route('admin.uploadFiles') }}">图片库</a>
    <a><cite>编辑</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md10 layui-col-md-offset1 layui-col-lg10 layui-col-lg-offset1 site-block">
            <fieldset class="layui-elem-field" style="height: 70%">
                <legend>图片库</legend>
                <div class="layui-field-box" style="margin-top: 30px">
                    <div style="width: 400px;height: 30px;float: right;margin-right: 110px;margin-bottom: 10px">
                        <div class="layui-form" style="width: 200px;float: left">
                            <select name="moveGroup" lay-filter="moveGroup">
                                <option value="">移动至</option>
                                @if(!empty($groups))
                                    @foreach($groups as $val=>$label)
                                        <option value="{{$label->id}}">{{$label->group_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="" style="float: left;margin-left: 4px">
                            <button type="button" class="layui-btn layui-btn-primary" onclick="destroyBat()"><i
                                        class="layui-icon layui-icon-delete"></i>删除
                            </button>
                        </div>
                        <div class="layui-upload" style="float: left;margin-left: 4px">
                            <button type="button" class="layui-btn layui-btn-primary" id="multipleUpload">+上传图片</button>
                        </div>
                    </div>
                    <div class="">
                        {{--防跨越--}}
                        {{ csrf_field() }}
                        <div class=" layui-col-md3" id="picMenu">
                            <ul class="layui-nav layui-nav-tree layui-inline" lay-filter="demo"
                                style="margin-right: 10px;">
                                <li class="layui-nav-item layui-nav-itemed">
                                    <dl class="layui-nav-child" style="background-color: #ffffff">
                                        <dd onmouseover="this.style.cursor='hand'" id="group_-1"
                                            style="background-color: #ffffff;"><a
                                                    href="{{url('admin/uploadFiles/create?group_id=-1')}}"
                                                    style="color: #000000">全部</a>
                                        </dd>
                                        <dd onmouseover="this.style.cursor='hand'" id="group_0"
                                            style="background-color: #ffffff;"><a
                                                    href="{{url('admin/uploadFiles/create?group_id=0')}}"
                                                    style="color: #000000">未分组</a></dd>
                                        @if(!empty($groups))
                                            @foreach($groups as $val=>$label)
                                                <dd id="group_{{$label->id}}"
                                                    style="background-color: #ffffff; cursor:pointer;"
                                                    onmouseover="this.style.cursor='hand'">
                                                    <a href="{{url('admin/uploadFiles/create?group_id').'='.$label->id}}"
                                                       style="color: #000000;display: inline">
                                                        <label style="color: #0C0C0C">{{$label->group_name}}</label>

                                                    </a>
                                                    <i class="layui-icon layui-icon-edit" style="color: #0C0C0C"
                                                       onclick="editGroup({{$label->id}})"></i>
                                                    <i class="layui-icon layui-icon-delete" style="color: #0C0C0C"
                                                       onclick="delGroup({{$label->id}})"></i>
                                                </dd>
                                            @endforeach
                                        @endif
                                        <dd style="background-color: #ffffff;"><a href="javascript:;" id="add_group"
                                                                                  style="color: #53bdff">新增分组+</a></dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>

                        <div class=" layui-col-md9" id="picContent">
                            {{--style="border: 1px solid #ffffff;float: right;width: 80%;height: 50%">--}}
                            <div id="file-list-body" class="v-box-body">
                                <ul class="file-list-item" style="display:flex;flex-direction:row;flex-wrap:wrap">
                                    @if(!empty($uploadFile))
                                        @foreach($uploadFile as $val=>$label)
                                            <li class="ng-scope" id="ng-scope_{{$label->id}}"
                                                onclick="chooseImg({{$label->id}})" title="{{$label->file_name}}"
                                                data-file-id="{{$label->id}}"
                                                data-file-path="{{ storage_image_url($label->file_url) }}"
                                                path="{{$label->file_url}}">
                                                <div class="img-cover"
                                                     style="background-image: url('{{ storage_image_url($label->file_url) }}')">
                                                </div>
                                                <p class="file-name am-text-center am-text-truncate">{{$label->file_name}}</p>
                                                <div class="select-mask" id="selectImg_{{$label->id}}" flag=0>
                                                    <img class="selectImg" src="{{asset('images/chose.png')}}">
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="layui-field-box" style="height: 45px;">
                                <div id="pages" style="float: right;"></div>
                            </div>
                        </div>


                    </div>
                </div>
            </fieldset>
        </div>

    </div>
    <style>
        .selectImg {
            position: absolute;
            top: 50px;
            left: 45px;
        }

        .ng-scope .active .select-mask {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            text-align: center;
            border-radius: 6px;
        }

        .layui-upload-img {
            width: 92px;
            height: 92px;
            margin: 10px;
        }

        .layui-nav-child a {
            width: 50%;
        }

        .ng-scope {
            position: relative;
            cursor: pointer;
            border-radius: 6px;
            padding: 10px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            /*float: left;*/
            margin: 10px;
            -webkit-transition: All 0.2s ease-in-out;
            -moz-transition: All 0.2s ease-in-out;
            -o-transition: All 0.2s ease-in-out;
            transition: All 0.2s ease-in-out;
        }

        .ng-scope:hover {
            border: 1px solid #53bdff;
        }

        .img-cover {
            width: 120px;
            height: 120px;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .file-name {
            margin: 5px 0 0 0;
            width: 120px;
            font-size: 5px;
        }

        .am-text-center {
            text-align: center !important;
        }

        .am-ellipsis, .am-text-truncate {
            word-wrap: normal;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .select-mask {
            display: none;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            text-align: center;
            border-radius: 6px;
        }

        .layui-nav-tree .layui-nav-child dd.layui-this, .layui-nav-tree .layui-nav-child dd.layui-this a, .layui-nav-tree .layui-this, .layui-nav-tree .layui-this > a, .layui-nav-tree .layui-this > a:hover {
            background-color: #eaf4fe;
            color: #0e90d2;
        }

    </style>
@endsection


@push('scripts')
    <script>
        $(function () {
            //获取url中的参数
            function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
                var r = window.location.search.substr(1).match(reg);  //匹配目标参数
                if (r != null) return unescape(r[2]);
                return null; //返回参数值
            }

            let group_id = getUrlParam('group_id');
            $('#group_' + group_id).css('background-color', '#eaf4fe');
        })

        // 图片分组查询
        function picGroup(group_id) {

            let url = "{{url('admin/uploadFiles/picGroup')}}?group_id=" + group_id;
            let _token = "{{csrf_token()}}";
            $.get(url, {"_token": _token}, function (result) {
                $('#picContent').html(result);
            });
        }

        // 分组编辑
        function editGroup(group_id) {
            let url = "{{ url('admin/uploadGroups/editWindow') }}/" + group_id;
            layer.open({
                type: 2,
                title: '编辑分组',
                area: ['400px', '310px'],
                fixed: false, //不固定
                maxmin: true,
                content: url,
                success: function () {


                }
            });


        }

        // 分组删除
        function delGroup(group_id) {

            layer.confirm('真的删除该分组吗', function (index) {

                let url = "{{ url('admin/uploadGroups/destroy') }}/" + group_id;
                let _token = "{{csrf_token()}}";
                $.get(url, {"_token": _token}, function (result) {
                    let msg = result.message;
                    if (!result.success) {
                        layer.open({
                            type: 1,
                            anim: 0,
                            title: msg,
                            area: ['50%', '70%'],
                            btn: ['关闭'],
                            content: JSON.stringify(result)
                        });
                    } else {
                        layer.alert(msg);
                        $("#group_" + group_id).remove();
                    }
                });
                layer.close(index);
            });
        }

        // 多选图片选中效果
        function chooseImg(imgId) {
            $("#ng-scope_" + imgId);
            if ($("#selectImg_" + imgId).attr('flag') == 0) {
                $("#ng-scope_" + imgId).addClass('active');
                $("#selectImg_" + imgId).css('display', 'block');
                $("#selectImg_" + imgId).attr('flag', 1);
            } else {
                $("#ng-scope_" + imgId).removeClass('active');
                $("#selectImg_" + imgId).css('display', 'none');
                $("#selectImg_" + imgId).attr('flag', 0);
            }
        }

        // 图片批量删除
        function destroyBat() {
            let ids = [];
            let _token = "{{csrf_token()}}";
            $(".active").each(function () {
                ids.push($(this).attr('data-file-id'));
            });
            if (ids.length === 0) {
                layer.msg('请选中需要删除的图片');
            } else {
                layer.confirm('确认删除所选图片吗？', function (index) {

                    $.ajax({
                        type: 'POST',
                        url: "{{route('admin.uploadFiles.destroyBat')}}",//发送请求
                        data: {ids: ids, _token: _token},
                        dataType: "JSON",
                        success: function (result) {
                            let msg = result.message;

                            if (!result.success) {
                                layer.open({
                                    type: 1,
                                    anim: 0,
                                    title: msg,
                                    area: ['50%', '70%'],
                                    btn: ['关闭'],
                                    content: JSON.stringify(result)
                                });

                            } else {
                                layer.alert(msg, function () {
                                    window.location.reload();
                                });
                            }
                        }
                    });

                });
            }


        }

        layui.use(['form', 'upload', 'laypage'], function () {
            let form = layui.form;
            let upload = layui.upload;
            let laypage = layui.laypage;

            // 图片移动分组
            form.on('select(moveGroup)', function (data) {
                let ids = [];
                let group_id = data.value;
                let _token = "{{csrf_token()}}";


                $(".active").each(function () {
                    ids.push($(this).attr('data-file-id'));
                });
                if (ids.length === 0) {
                    layer.msg('请选中需要移动的图片');

                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{route('admin.uploadFiles.updateBat')}}",//发送请求
                        data: {ids: ids, group_id: group_id, _token: _token},
                        dataType: "JSON",
                        success: function (result) {
                            let msg = result.message;

                            if (!result.success) {
                                layer.open({
                                    type: 1,
                                    anim: 0,
                                    title: msg,
                                    area: ['50%', '70%'],
                                    btn: ['关闭'],
                                    content: JSON.stringify(result)
                                });

                            } else {
                                layer.alert(msg, function () {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                }


            });

            //多图片上传
            let _token = "{{csrf_token()}}";
            let upload_url = '{{ route('admin.uploadFiles.uploader') }}?file_type=image&folder=pics&_token=' + _token;
            upload.render({
                elem: '#multipleUpload'
                , url: upload_url
                , field: 'upload_file'//设定文件域的字段名 默认file
                , accept: 'image'
                , multiple: true
                , done: function (res, index, upload) {
                    if (res.code == 0) { //上传成功
                        layer.msg(res.msg);
                        window.location.reload();
                        return;
                    }
                    this.error(index, upload);
                }
                , error: function (index, upload) {
                    layer.msg('上传失败');
                }
            });


            // 添加分頁
            laypage.render({
                elem: 'pages',//注意，这里的 test1 是 ID，不用加 # 号
                count: "{{$uploadFile->total()}}",//数据总数，从服务端得到
                groups: 5,//连续显示分页数,
                limit: 18, //限制
                theme: '#597ef7',
                curr: "{{$uploadFile->currentPage()}}",//当前页数,
                jump: function (obj, first) {
                    //得到了当前页，用于向服务端请求对应数据
                    var curr = obj.curr;
                    if (!first) {
                        // layer.msg('第 '+ obj.curr +' 页');
                        let group_id = "{{$group_id}}";
                        tmp = "&{{$group_id != ''?'group_id='.$group_id:''}}"
                        window.location.href = "{{ url("admin/uploadFiles/create?")}}" + "page=" + curr + tmp;
                    }

                }
            });
        });

        // 添加分组
        $("#add_group").on("click", function () {
            let url = "{{ route('admin.uploadGroups.createWindow') }}";
            layer.open({
                type: 2,
                title: '添加分组',
                area: ['400px', '310px'],
                fixed: false, //不固定
                maxmin: true,
                content: url,

            });
        })

    </script>
@endpush