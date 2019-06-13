@extends('backend.layouts.app')

@section('title', $title = $deliveries->id ? '编辑' : '添加' )
@php
$key = 0;
@endphp
@section('breadcrumb')
    <a>配送设置</a>
    <a href="{{ route('admin.deliveries') }}">模板</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md10 layui-col-md-offset1 layui-col-lg10 layui-col-lg-offset1 site-block">
            <fieldset class="layui-elem-field">
                <legend>运费模版 - {{$title}}</legend>
                <div class="layui-field-box">

                    <div class="layui-form">
                        {{--防跨越--}}
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label class="layui-form-label">模版名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" required lay-verify="required" placeholder="请输入模版名称"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('name',$deliveries->name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">计费方式</label>
                            <div class="layui-input-block">
                                <input type="radio" name="method" value="{{\App\Models\Deliveries::METHOD_PIECE}}" title="按件数" lay-filter="method_type"
                                       @if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_PIECE) checked="checked" @endif>
                                <input type="radio" name="method" value="{{\App\Models\Deliveries::METHOD_WEIGHT}}" title="按重量" lay-filter="method_type"
                                       @if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_WEIGHT) checked="checked" @endif>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">配送区域</label>
                            <div class="layui-input-block">
                                <button class="layui-btn layui-btn-primary layui-btn-sm" id="addRow" >添加可配送区域+</button>
                                <table class="layui-table" id="tbl_bj">
                                    <colgroup>
                                        <col width="250">
                                        <col width="60">
                                        <col width="60">
                                        <col width="60">
                                        <col width="60">
                                        <col width="40">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>可配送区域</th>
                                        <th>首<font class="method">
                                                @if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_PIECE)件@else重 @endif</font>
                                            (<font class="methodunit">@if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_PIECE)个@else KG @endif</font>)
                                        </th>
                                        <th>运费 (元)</th>
                                        <th>续<font class="method">  @if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_PIECE)件@else重 @endif</font>
                                            (<font class="methodunit">@if(old('method',$deliveries->method) == \App\Models\Deliveries::METHOD_PIECE)个@else KG @endif</font>)</th>
                                        <th>续费 (元)</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($deliveries->rules)
                                        @foreach($deliveries->rules as $key => $item)
                                            <tr id="0" flag="{{$key}}" xmlns="http://www.w3.org/1999/html">
                                                <td style="width: 90px">
                                                    {!!htmlspecialchars_decode($item->regionsElement)!!}
                                                    <input type="hidden" name="rules[{{$key}}][region]"
                                                           value="{{$item['region']}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="rules[{{$key}}][first]" class="layui-input"
                                                           value="{{$item->first}}" required lay-verify="required">
                                                </td>
                                                <td>
                                                    <input type="text" name="rules[{{$key}}][first_fee]"
                                                           class="layui-input" value="{{$item->first_fee}}" required
                                                           lay-verify="required">
                                                </td>
                                                <td>
                                                    <input type="text" name="rules[{{$key}}][additional]"
                                                           class="layui-input" value="{{$item->additional}}" required
                                                           lay-verify="required">
                                                </td>
                                                <td>
                                                    <input type="text" name="rules[{{$key}}][additional_fee]"
                                                           class="layui-input" value="{{$item->additional_fee}}"
                                                           required lay-verify="required">
                                                </td>
                                                <td>
                                                    <div class="layui-btn-group">
                                                        <button class="layui-btn layui-btn-primary layui-btn-sm chooseAddress">
                                                            <i class="layui-icon  layui-icon-edit"></i></button>
                                                        <button value=""
                                                                class="layui-btn layui-btn-primary layui-btn-sm deleteRow">
                                                            <i class="layui-icon layui-icon-delete"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-block">
                                <input type="text" name="sort" required lay-verify="required" placeholder="请输入排序"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('sort',$deliveries->sort) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
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

        layui.use('form', function () {
            let form = layui.form;

            // 计费方式 按件数0||按重量1
            form.on('radio(method_type)', function (data) {

                tab = data.value;
                if (tab == "{{\App\Models\Deliveries::METHOD_WEIGHT}}") {
                   $('.method').html('重');
                   $('.methodunit').html('KG');
                } else {
                    $('.method').html('件');
                    $('.methodunit').html('个');

                }
            });

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$deliveries->id?route('admin.deliveries.update',$deliveries->id):route('admin.deliveries.store')}}";

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
                                window.location.href = "{{ url('admin/deliveries/edit') }}/" + result.model.id;
                            });
                        }
                    }
                });
            });
            // 添加规格
            var num = 0;
            tmp = "{{$deliveries->id}}";
            if (tmp != '') {
                num = "{{$key+2}}";
            }
            // 表格添加
            $('#addRow').on('click', function (data) {
                let trHtml = '<tr id="0" flag="' + num + '" xmlns="http://www.w3.org/1999/html">\n' +
                    '                                        <td  style="width: 90px">\n' +
                    '                                                <button class="layui-btn layui-btn-primary layui-btn-sm chooseAddress">\n' +
                    '                                                    <i class="layui-icon  layui-icon-location "></i>点击选择可配送区域</button>\n' +
                    '                                        </td>\n' +
                    '                                        <td>\n' +
                    '                                            <input type="hidden" name="rules[' + num + '][id]" >\n' +
                    '                                            <input type="text" name="rules[' + num + '][first]" class="layui-input"  required="" lay-verify="required" >\n' +
                    '                                        </td>\n' +
                    '                                        <td>\n' +
                    '                                            <input type="text" name="rules[' + num + '][first_fee]" class="layui-input" required="" lay-verify="required"  >\n' +
                    '                                        </td>\n' +
                    '                                        <td>\n' +
                    '                                            <input type="text" name="rules[' + num + '][additional]" class="layui-input" required="" lay-verify="required"  >\n' +
                    '                                        </td>\n' +
                    '                                        <td>\n' +
                    '                                            <input type="text" name="rules[' + num + '][additional_fee]" class="layui-input" required="" lay-verify="required" >\n' +
                    '                                        </td>\n' +
                        '<td>\n' +
                    '                                                    <div class="layui-btn-group">\n' +
                    '                                                        <button class="layui-btn layui-btn-primary layui-btn-sm chooseAddress">\n' +
                    '                                                            <i class="layui-icon  layui-icon-edit"></i></button>\n' +
                    '                                                        <button value="" class="layui-btn layui-btn-primary layui-btn-sm deleteRow">\n' +
                    '                                                            <i class="layui-icon layui-icon-delete"></i></button>\n' +
                    '                                                    </div>\n' +
                    '                                                </td> '+
                    '                                    </tr>';

                $("#tbl_bj tbody").append(trHtml)
                bindAddress();
                delAddress();
                num++;
            })
        });
        bindAddress();
        delAddress();
        // 打开addressWindow选择地区
        function bindAddress() {

            $("button.chooseAddress").off('click').on('click', function () {
                let tr = $(this).parents('tr');
                let url = "{{ route('admin.regions.addressWindow') }}";
                layer.open({
                    type: 2,
                    title: '选择可配送区域',
                    fixed: false, //不固定
                    area: ['45%', '70%'],
                    maxmin: true,
                    content: url,
                    btn: '确定',
                    yes: function (index, layero) {
                        var body = top.layer.getChildFrame('body', index);
                        let ids = [];
                        let regions = [];
                        let regionsElement = '';
                        $(body).find("input[name='addressIds']:checked").each(function () {
                            ids.push($(this).val());
                        });
                        for (var i = 0; i < ids.length; i++) {
                            regions[i] = [];
                            regions[i]['name'] = $(body).find("#span_" + ids[i]).text();
                            regions[i]['pid'] = $(body).find("#span_" + ids[i]).attr('data-pid');
                            regions[i]['id'] = ids[i];

                            if (regions[i]['pid'] == 0) {
                                regionsElement = regionsElement.substring(0,regionsElement.length-1);
                                regionsElement += ')' + regions[i]['name'] + '( ';
                            } else {
                                regionsElement +=  regions[i]['name'] + '、';
                            }

                        }
                        regionIds = ids.join(',');
                        regionsElement = regionsElement.substring(0,regionsElement.length-1);
                        regionsElement = regionsElement.substr(1);
                        regionsElement += ')<input type="hidden" name="rules[' + $(tr).attr('flag') + '][region]" value="' + regionIds + '">';


                        $(tr).children(":first").html('');
                        $(tr).children(":first").append(regionsElement);

                        layer.close(index); //如果设定了yes回调，需进行手工关闭
                    }

                });


            })
        }
        // 删除配送规则
        function delAddress() {

            $("button.deleteRow").off('click').on('click', function () {
                let objTr = $(this).closest('tr');
                layer.confirm('真的删除行么', function (index) {

                     objTr.remove();
                     layer.close(index);

                });
            })
        }
    </script>
@endpush