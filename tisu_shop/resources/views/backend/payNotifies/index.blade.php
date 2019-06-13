@extends('backend.layouts.app')

@section('title', $title = '支付结果')

@section('breadcrumb')

    <a>支付相关</a>
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
                            <input type="text" class="layui-input" name="appid" placeholder="appID查询">
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="out_trade_no" placeholder="订单编号查询">
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
        <script type="text/html" id="bartbl">
            <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
            <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="rtnPay">退款</a>
            {{--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}
            {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
        </script>
    </div>
@endsection

@push('scripts')
    <script>

        //删除 行
        function deleteRow(id, obj) {
            let url = "{{ url('admin/payNotifies/destroy') }}/" + id;
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

            let url = "{{ url('admin/payNotifies/destroyBat') }}";
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

        //申请退款
        function refund(id) {
            let url = "{{ url('admin/payNotifies/refund') }}/" + id;
            let _token = "{{csrf_token()}}";

            $.get(url, {"_token": _token}, function (result) {
                let msg = result.message;
                layer.open({
                    type: 1,
                    anim: 0,
                    title: msg,
                    area: ['50%', '70%'],
                    btn: ['关闭'],
                    content: JSON.stringify(result)
                });
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
                , url: "{{route('admin.payNotifies.list')}}"  //数据接口
                , title: "{{$title}}"
                , page: true //开启分页
                , toolbar: 'default' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
                // , totalRow: true //开启合计行
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'appid', title: '小程序ID', width: 120}
                    , {field: 'mch_id', title: '商户号', width: 120}
                    , {field: 'out_trade_no', title: '商户订单号', width: 200}
                    , {field: 'transaction_id', title: '微信支付订单号', width: 200}
                    // , {field: 'attach', title: '商家数据包', width: 120}
                    , {field: 'bank_type', title: '付款银行', width: 120}
                    , {field: 'cash_fee', title: '现金支付金额', width: 120}
                    // , {field: 'cash_fee_type', title: '现金支付货币类型', width: 120}
                    // , {field: 'coupon_count', title: '代金券使用数量', width: 120}
                    // , {field: 'coupon_fee', title: '总代金券金额', width: 120}
                    // , {field: 'coupon_fee_$n', title: '单个代金券支付金额', width: 120}
                    // , {field: 'coupon_id_$n', title: '代金券ID', width: 120}
                    // , {field: 'coupon_type_$n', title: '代金券类型', width: 120}
                    // , {field: 'device_info', title: '设备号	', width: 120}
                    , {field: 'fee_type', title: '货币种类', width: 120}
                    // , {field: 'is_subscribe', title: '是否关注公众账号', width: 120}
                    // , {field: 'nonce_str', title: '随机字符串', width: 120}
                    , {field: 'openid', title: '用户标识', width: 120}
                    , {field: 'result_code', title: '业务结果', width: 120}
                    // , {field: 'sign', title: '签名', width: 120}
                    , {
                        field: 'time_end', title: '支付完成时间', width: 180, templet: function (row) {
                            return row.time_end ? dateFormt(row.time_end) : '';
                        }
                    }
                    , {field: 'total_fee', title: '订单总金额，单位为分	', width: 120}
                    , {field: 'return_msg', title: '返回信息', width: 120}
                    , {field: 'settlement_total_fee', title: '应结订单金额', width: 120}
                    , {field: 'trade_type', title: '交易类型', width: 120}
                    , {field: 'err_code', title: '错误代码', width: 120}
                    , {field: 'err_code_des', title: '错误代码描述	', width: 120}
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
                        window.location.href = "{{ route('admin.payNotifies.create') }}";
                        break;
                    case 'update':
                        if (data.length === 0) {
                            layer.msg('请选择一行');
                        } else if (data.length > 1) {
                            layer.msg('只能同时编辑一个');
                        } else {
                            // layer.alert('编辑 [id]：' + checkStatus.data[0].id);
                            window.location.href = "{{ url('admin/payNotifies/edit') }}/" + checkStatus.data[0].id;
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
                } else if (layEvent === 'rtnPay') {
                    layer.msg('退款');
                    refund(data.id);
                } else if (layEvent === 'del') {

                    layer.confirm('真的删除行么？', function (index) {

                        //向服务端发送删除指令
                        deleteRow(data.id, obj);
                        // obj.del(); //删除对应行（tr）的DOM结构
                        layer.close(index);

                    });
                } else if (layEvent === 'edit') {
                    //跳转到编辑页面
                    window.location.href = "{{ url('admin/payNotifies/edit') }}/" + data.id;
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