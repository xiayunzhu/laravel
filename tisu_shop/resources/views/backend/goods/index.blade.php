@extends('backend.layouts.app')

@section('title', $title = '商品列表')

@section('breadcrumb')

    <a>售卖商品</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div>
        <h2>{{$title}}</h2>
    </div>
    <hr class="layui-bg-green">
    <div style="padding: 10px;">
        <div class="layui-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="id" placeholder="ID查询">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="name" placeholder="名称查询">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="shop_id" placeholder="shopID查询">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <button class="layui-btn" id="search" lay-submit lay-filter="formSearch">查询</button>
                        <button class="layui-btn layui-btn-primary" id="clear">清空</button>
                    </div>
                </div>

            </div>
        </div>
        <table id="tbl" lay-filter="tblFilter"></table>
        <script type="text/html" id="bartbl">
            <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
            {{--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    </div>
@endsection

@push('scripts')
    <script>

        //删除 行
        function deleteRow(id, obj) {
            let url = "{{ url('admin/goods/destroy') }}/" + id;
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
                    layer.msg(msg);
                    //layer.alert(msg);
                    obj.del(); //删除对应行（tr）的DOM结构
                }
            });
        }

        // 批量删除
        function deleteRows(ids) {

            let url = "{{ url('admin/goods/destroyBat') }}";
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
                        layer.alert(msg, function () {
                            window.location.reload();
                        });

                    }

                }
            });
        }

        //渲染table
        layui.use(['table', 'form'], function () {
            let table = layui.table;
            let form = layui.form;
            //执行一个table 实例
            table.render({
                elem: '#tbl'
                // , height: 312
                , url: "{{route('admin.goods.list')}}"  //数据接口
                , title: "{{$title}}"
                , page: true //开启分页
                // , toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                // , totalRow: true //开启合计行
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'name', title: '商品名称', width: 120}
                    , {field: 'title', title: '商品标题', width: 220}
                    // , {field: 'brand_id', title: '品牌ID', width: 120}
                    // , {field: 'category_id', title: '类目ID', width: 120}
                    // , {field: 'content', title: '商品详情', width: 120}
                    // , {field: 'deduct_stock_type', title: '扣减库存的方式', width: 120}
                    // , {field: 'delivery_id', title: '运费模版', width: 120}
                    // , {field: 'goods_sort', title: '商品排序', width: 120}
                    , {field: 'introduction', title: '商品简介'}
                    , {field: 'publish_status', title: '发布状态', width: 120}
                    , {field: 'sales_actual', title: '实际销售', width: 120}
                    , {field: 'sales_initial', title: '初始销量', width: 120}
                    , {field: 'sales_status', title: '商品状态', width: 120}
                    , {field: 'shop_id', title: '店铺ID', width: 120}
                    // , {field: 'spec_type', title: '规格类型', width: 120}
                    , {field: 'version', title: '版本号', width: 120}
                    , {field: 'created_at', title: '创建时间', width: 180, sort: true}
                    , {field: 'updated_at', title: '修改时间', width: 180, sort: true}
                    , {fixed: 'right', width: 120, align: 'center', toolbar: '#bartbl'}
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
                        window.location.href = "{{ route('admin.goods.create') }}";
                        break;
                    case 'update':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else if (data.length > 1) {
                            layer.msg('只能同时编辑一个');
                        } else {
                            // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                            window.location.href = "{{ url('admin/goods/edit') }}/" + checkStatus.data[0].id;
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
                    window.location.href = "{{ url('admin/goods/detail') }}/" + data.id;
                } else if (layEvent === 'del') {

                    layer.confirm('真的删除行么？', function (index) {

                        //向服务端发送删除指令
                        deleteRow(data.id, obj);
                        // obj.del(); //删除对应行（tr）的DOM结构
                        layer.close(index);

                    });
                } else if (layEvent === 'edit') {
                    //跳转到编辑页面
                    window.location.href = "{{ url('admin/goods/edit') }}/" + data.id;
                }
            });

            //监听查询按钮
            form.on('submit(formSearch)', function (data) {
                let queryFields = data.field;
                //表格数据重载
                table.reload('tbl', {
                    where: {
                        queryFields
                    },
                    page: {
                        curr: 1
                    }
                });

            });

            //清空 条件
            $("#clear").on('click', function () {
                layer.msg('清空条件');
                window.location.reload();
                // $("#queryForm").find('input[type=text],select,input[type=hidden]').each(function () {
                //     $(this).val();
                // })
            })

        });
    </script>
@endpush