@extends('backend.layouts.app')

@section('title', $title = '退款记录')

@section('breadcrumb')

    <a>订单管理</a>
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
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="appid" placeholder="小程序查询">
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="out_trade_no" placeholder="商品订单查询">
                        </div>
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
        {{--<script type="text/html" id="bartbl">--}}
        {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>--}}
        {{--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}
        {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
        {{--</script>--}}
    </div>
@endsection

@push('scripts')
    <script>

        //删除 行
        function deleteRow(id, obj) {
            let url = "{{ url('admin/payRefunds/destroy') }}/" + id;
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

            let url = "{{ url('admin/payRefunds/destroyBat') }}";
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
                , url: "{{route('admin.payRefunds.list')}}"  //数据接口
                , title: "{{$title}}"
                , page: true //开启分页
                // , toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                // , totalRow: true //开启合计行
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'return_code', title: '返回状态码', width: 120}
                    , {field: 'err_code', title: '错误代码', width: 120}
                    , {field: 'err_code_des', title: '错误代码描述', width: 120}
                    , {field: 'appid', title: '小程序ID', width: 120}
                    , {field: 'mch_id', title: '商户号', width: 120}
                    , {field: 'nonce_str', title: '随机字符串', width: 120}
                    , {field: 'sign', title: '签名', width: 120}
                    , {field: 'transaction_id', title: '微信订单号', width: 120}
                    , {field: 'out_trade_no', title: '商户订单号	', width: 120}
                    , {field: 'out_refund_no', title: '商户退款单号', width: 120}
                    , {field: 'refund_id', title: '微信退款单号', width: 120}
                    , {field: 'refund_fee', title: '退款总金额,单位为分,可以做部分退款', width: 120}
                    , {field: 'settlement_refund_fee', title: '应结退款金额', width: 120}
                    , {field: 'total_fee', title: '标价金额', width: 120}
                    , {field: 'settlement_total_fee', title: '应结订单金额', width: 120}
                    , {field: 'fee_type', title: '标价币种', width: 120}
                    , {field: 'cash_fee', title: '现金支付金额', width: 120}
                    , {field: 'cash_fee_type', title: '现金支付币种', width: 120}
                    , {field: 'cash_refund_fee', title: '现金退款金额', width: 120}
                    , {field: 'coupon_type_$n', title: '代金券类型', width: 120}
                    , {field: 'coupon_refund_fee', title: '代金券退款总金额', width: 120}
                    , {field: 'coupon_refund_fee_$n', title: '单个代金券退款金额', width: 120}
                    , {field: 'coupon_refund_count', title: '退款代金券使用数量', width: 120}
                    , {field: 'coupon_refund_id_$n', title: '退款代金券ID', width: 120}
                    , {field: 'return_msg', title: '返回信息', width: 120}

                    , {field: 'created_at', title: '创建时间', width: 180, sort: true}
                    , {field: 'updated_at', title: '修改时间', width: 180, sort: true}
                    // , {fixed: 'right', width: 165, align: 'center', toolbar: '#bartbl'}
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
                        window.location.href = "{{ route('admin.payRefunds.create') }}";
                        break;
                    case 'update':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else if (data.length > 1) {
                            layer.msg('只能同时编辑一个');
                        } else {
                            // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                            window.location.href = "{{ url('admin/payRefunds/edit') }}/" + checkStatus.data[0].id;
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
                    window.location.href = "{{ url('admin/payRefunds/edit') }}/" + data.id;
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