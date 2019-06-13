@extends('backend.layouts.plain')
@section('title', '选择商品' )
@section('content')
    <div class="layui-col-md12">
        <div class="layui-field-box">
            <div class="layui-form">
                <div class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="id" placeholder="ID查询">
                                </div>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="shop_nick" placeholder="店铺昵称">
                                </div>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="shop_name" placeholder="店铺名称">
                                </div>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" id="language" name="seller"
                                           placeholder="归属的用户（卖家）">
                                </div>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <button class="layui-btn" id="search" lay-submit lay-filter="formSearch">查询</button>
                                <button class="layui-btn layui-btn-primary" id="clear">清空</button>
                            </div>
                        </div>
                        <td data-field="0" data-key="1-0-0" class="layui-table-col-special">
                            <div class="layui-table-cell laytable-cell-1-0-0 laytable-cell-checkbox"><input
                                        type="checkbox" name="layTableCheckbox" lay-skin="primary">
                                <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary"><i
                                            class="layui-icon layui-icon-ok"></i></div>
                            </div>
                        </td>
                        <td data-field="id" data-key="1-0-1" class="">
                            <div class="layui-table-cell laytable-cell-1-0-1">4107</div>
                        </td>
                    </div>
                </div>
                <table id="chooseERPTable" lay-filter="tblFilter"></table>
                <script type="text/html" id="bartbl">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                </script>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{asset('js/jquery-ui.js')}}"></script>

    <script>

        // 卖家搜索
        let url = "{{url('admin/sellers/list')}}";
        let _token = "{{csrf_token()}}";

        $.get(url, {"_token": _token}, function (result) {
            var name = [];
            if (result.success) {
                $.each(result.model.data, function (index, obj) {
                    name.push(obj.name)
                });
                $("#language").autocomplete({
                    source: name
                });
            }
        });

        //渲染table
        layui.use(['table', 'form'], function () {
            let table = layui.table;
            let form = layui.form;
            //执行一个table 实例
            table.render({
                elem: '#chooseERPTable'
                , url: "{{route('admin.products.list')}}"  //数据接口
                , title: "ERP商品"
                , page: true //开启分页
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                    , {field: 'spec_code', title: '规格编码', width: 120}
                    , {field: 'item_code', title: '商品编码', width: 120}
                    , {field: 'item_name', title: '商品名称', width: 220}
                    , {field: 'bar_code', title: '商品条码', width: 120}
                    , {field: 'article_number', title: '货号', width: 120}
                    , {field: 'color', title: '颜色', width: 120}
                    , {field: 'other_prop', title: '其他规格', width: 120}
                    , {field: 'price', title: '标价', width: 120}
                    , {
                        field: 'quantity0', title: '实际库存', width: 180, templet: function (row) {
                            if (row.stock == null) {
                                return 0;
                            } else {
                                return row.stock.quantity;
                            }
                        }
                    }
                    , {
                        field: 'available0', title: '可用库存', width: 180, templet: function (row) {
                            if (row.stock == null) {
                                return 0;
                            } else {
                                return row.stock.available;
                            }
                        }
                    }
                    , {field: 'unit', title: '单位', width: 80}
                    , {
                        fixed: 'right',
                        title: '操作项',
                        width: 100,
                        align: 'center',
                        toolbar: '#bartblProduct'
                    }
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

            //监听头工具栏事件
            table.on('toolbar(tblFilter)', function (obj) {

                let checkStatus = table.checkStatus(obj.config.id)
                    , data = checkStatus.data; //获取选中的数据
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
            })

        });
    </script>
@endpush