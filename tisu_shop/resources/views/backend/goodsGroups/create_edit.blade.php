@extends('backend.layouts.app')

@section('title', $title = $goodsGroup->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.goodsGroups') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
         <fieldset class="layui-elem-field">
            <legend>详情 - {{$title}}</legend>
            <div class="layui-field-box">

                <div class="layui-form">
                    {{--防跨越--}}
                    {{ csrf_field() }}
                                 <div class="layui-form-item">
                    <label class="layui-form-label">分组名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入分组名称" autocomplete="off" class="layui-input" value="{{ old('name',$goodsGroup->name) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">归属店铺</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入归属店铺" autocomplete="off" class="layui-input" value="{{ old('shop_id',$goodsGroup->shop_id) }}">
                    </div>
                </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                            {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
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

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$goodsGroup->id?route('admin.goodsGroups.update',$goodsGroup->id):route('admin.goodsGroups.store')}}";

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

                                window.location.href = "{{ url('admin/goodsGroups/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush