@extends('backend.layouts.app')

@section('title', $title = '权限列表')

@section('breadcrumb')

    <a>系统设置</a>
    <a><cite>{{$title}}</cite></a>
    {{--<a href="{{ route('admin.users.create') }}">新增用户</a>--}}
@endsection

@section('content')
    <div>
        <h2>{{$title}}</h2>
    </div>
    <hr class="layui-bg-green">
    <div style="padding: 10px;">
        <table id="tbl" lay-filter="tblFilter"></table>
        <script type="text/html" id="bartbl">
            {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>--}}
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
@endsection

@push('scripts')
    <script>

        //删除 行
        function deleteRow(id, obj) {
            let url = "{{ url('admin/permissions/destroy') }}/" + id;
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
                    // layer.msg(msg);
                    layer.alert(msg);
                    obj.del(); //删除对应行（tr）的DOM结构
                }
            });
        }

        // 批量删除
        function deleteRows(ids) {

            let url = "{{ url('admin/permissions/destroyBat') }}";
            let data = {ids: ids};
            data['_token'] = "{{csrf_token()}}";

            $.ajax({
                type: 'POST',
                url: url,//发送请求
                data: data,
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
                        // layer.msg(msg);
                        layer.alert(msg,function () {
                            window.location.reload();
                        });

                    }

                }
            });
        }

        //渲染table
        layui.use('table', function () {
            let table = layui.table;
            //执行一个table 实例
            table.render({
                elem: '#tbl'
                // , height: 312
                , url: "{{route('admin.permissions.list')}}"  //数据接口
                , title: '权限列表'
                , page: true //开启分页
                , toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                // , totalRow: true //开启合计行
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'name', title: '权限名称', width: 300}
                    , {field: 'remarks', title: '权限备注'}
                    , {field: 'created_at', title: '创建时间', width: 180, sort: true}
                    , {field: 'updated_at', title: '修改时间', width: 180, sort: true}
                    , {fixed: 'right', width: 165, align: 'center', toolbar: '#bartbl'}
                ]]
                , parseData: function (res) {
                    return {
                        "code": res.success ? 0 : 1, //解析接口状态
                        "msg": res.message, //解析提示文本
                        "count": res.model.total, //解析数据长度
                        "data": res.model.data //解析数据列表
                    };
                }
            });

            //监听头工具栏事件
            table.on('toolbar(tblFilter)', function (obj) {

                let checkStatus = table.checkStatus(obj.config.id)
                    , data = checkStatus.data; //获取选中的数据
                switch (obj.event) {
                    case 'add':
                        layer.msg('添加');
                        window.location.href = "{{ route('admin.permissions.create') }}";
                        break;
                    case 'update':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else if (data.length > 1) {
                            layer.msg('只能同时编辑一个');
                        } else {
                            // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                            window.location.href = "{{ url('admin/permissions/edit') }}/" + checkStatus.data[0].id;
                        }
                        break;
                    case 'delete':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else {

                            let ids = [];
                            for (let i in data) {
                                ids.push(data[i].id);
                            }
                            if (ids.length > 0) {
                                layer.confirm('真的删除选中行么', function (index) {

                                    //向服务端发送删除指令
                                    deleteRows(ids);
                                    // obj.del(); //删除对应行（tr）的DOM结构
                                    layer.close(index);

                                });

                            } else {
                                layer.msg('批量删除对象获取失败');
                            }
                        }
                        break;
                }

            });

            //监听行工具事件
            table.on('tool(tblFilter)', function (obj) { //注：tool 是工具条事件名，tblFilter 是 table 原始容器的属性 lay-filter="对应的值"
                let data = obj.data //获得当前行数据
                    , layEvent = obj.event; //获得 lay-event 对应的值
                if (layEvent === 'detail') {
                    layer.msg('查看操作');
                } else if (layEvent === 'del') {

                    layer.confirm('真的删除行么？', function (index) {

                        //向服务端发送删除指令
                        deleteRow(data.id, obj);
                        // obj.del(); //删除对应行（tr）的DOM结构
                        layer.close(index);

                    });
                } else if (layEvent === 'edit') {
                    //跳转到编辑页面
                    window.location.href = "{{ url('admin/permissions/edit') }}/" + data.id;
                }
            });

        });
    </script>
@endpush