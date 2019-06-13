@extends('backend.layouts.app')

@section('title', $title = $wxapp->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>微信小程序管理</a>
    <a href="{{ route('admin.wxapps') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                <fieldset class="layui-elem-field">
                    <legend>小程序资料</legend>
                    <div class="layui-field-box">
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label">小程序名称<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="app_name" required lay-verify="required" placeholder="请输入小程序名称"
                                       autocomplete="off" class="layui-input" value="{{ old('app_name',$wxapp->app_name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">小程序ID<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="app_id" required lay-verify="required" placeholder="请输入小程序ID"
                                       autocomplete="off" class="layui-input" value="{{ old('app_id',$wxapp->app_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">小程序密钥<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="text" name="app_secret" required lay-verify="required" placeholder="请输入小程序密钥"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('app_secret',$wxapp->app_secret) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">店铺ID<i style="color: red">*</i></label>
                                <div class="layui-input-inline">
                                    <input type="text" name="shop_id" id="shopIdInput" disabled required lay-verify="required" placeholder="店铺ID"
                                           autocomplete="off" class="layui-input" value="{{ old('shop_id',$wxapp->shop_id) }}">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">
                                    <a id="chooseShop">选择店铺
                                        <i class="layui-icon layui-icon-add-1"></i></a></label>
                                <div class="layui-input-inline">

                                    <input type="text" id="shopNameInput" required
                                           lay-verify="required"
                                           placeholder="请选择店铺"
                                           autocomplete="off" class="layui-input"
                                           value="{{old('shop_nick',$wxapp->shop_nick )}}" disabled>

                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否服务<i style="color: red">*</i></label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_service" value="1" title="是"
                                       @if(old('is_service',$wxapp->is_service) == 1) checked="checked" @endif>
                                <input type="radio" name="is_service" value="0" title="否"
                                       @if(old('is_service',$wxapp->is_service) == 0) checked="checked" @endif>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">微信支付商户号</label>
                            <div class="layui-input-block">
                                <input type="text" name="mchid"  placeholder="请输入微信支付商户号"
                                       autocomplete="off" class="layui-input" value="{{ old('mchid',$wxapp->mchid) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">微信支付密钥</label>
                            <div class="layui-input-block">
                                <input type="text" name="apikey" placeholder="请输入微信支付密钥"
                                       autocomplete="off" class="layui-input" value="{{ old('apikey',$wxapp->apikey) }}">
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
            <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="choose">选择店铺</a></script>
    </div>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use('form', function () {
            let form = layui.form;

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$wxapp->id?route('admin.wxapps.update',$wxapp->id):route('admin.wxapps.store')}}";

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

                                window.location.href = "{{ url('admin/wxapps/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
            // 选卖家用户
            $("#chooseShop").on('click', function () {

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
                    '<div><table id="chooseShopTable" lay-filter="tblArtistTable"></table></div></div>';

                layer.open({
                    type: 1,
                    area: ['80%', '60%'],
                    content: content,
                    success: function (layero, index) {
                        let table = layui.table;
                        table.render({
                            elem: '#chooseShopTable'
                            , url: "{{route('admin.shops.list')}}"  //数据接口
                            , title: "店铺列表"
                            , page: true //开启分页
                            , cols: [[ //表头
                                {field: 'id', title: 'ID', width: 50}
                                , {field: 'shop_id', title: '店铺ID', width: 120}
                                , {field: 'shop_nick', title: '店铺昵称', width: 120}
                                , {field: 'shop_name', title: '店铺名称', width: 120}
                                ,{
                                    field: 'image', title: '店铺图标', width: 120, templet: function (row) {
                                        let src = row.icon_url && row.icon_url ? row.icon_url : '';
                                        if (src) {
                                            let imgHtml = '<a href="' + src + '" target="_blank"><img  src="' + src + '" alt="' + src + '"></a>';
                                            return imgHtml
                                        }
                                        return src;
                                    }
                                }
                                , {field: 'introduction', title: '店铺简介', width: 120}
                                , {field: 'username', title: '归属的用户(卖家)', width: 120,templet:function (row) {
                                        return row.user.name
                                    }}

                                , {field: 'created_at', title: '创建时间', width: 180, sort: true}
                                , {field: 'updated_at', title: '修改时间', width: 180, sort: true}
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
                                $("#shopIdInput").val(data.id);
                                $("#shopNameInput").val(data.shop_nick);


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
        });
    </script>
@endpush