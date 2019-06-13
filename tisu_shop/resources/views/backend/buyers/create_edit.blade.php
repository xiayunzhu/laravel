@extends('backend.layouts.app')

@section('title', $title = $buyer->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>买家管理</a>
    <a href="{{ route('admin.buyers') }}">买家列表</a>
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
                            <label class="layui-form-label">open_id</label>
                            <div class="layui-input-block">
                                <input type="text" name="open_id" required lay-verify="required"
                                       placeholder="请输入用户小程序的open_id" autocomplete="off" class="layui-input"
                                       value="{{ old('open_id',$buyer->open_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">手机号</label>
                            <div class="layui-input-block">
                                <input type="text" name="phone" required lay-verify="required" placeholder="请输入手机号"
                                       autocomplete="off" class="layui-input" value="{{ old('phone',$buyer->phone) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">unionId</label>
                            <div class="layui-input-block">
                                <input type="text" name="union_id" required lay-verify="required"
                                       placeholder="请输入微信小程序的 unionId" autocomplete="off" class="layui-input"
                                       value="{{ old('union_id',$buyer->union_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">微信昵称</label>
                            <div class="layui-input-block">
                                <input type="text" name="nick_name" required lay-verify="required" placeholder="请输入微信昵称"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('nick_name',$buyer->nick_name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">头像链接</label>
                            <div class="layui-input-block">
                                <input type="text" name="avatar_url" required lay-verify="required"
                                       placeholder="请输入头像链接" autocomplete="off" class="layui-input"
                                       value="{{ old('avatar_url',$buyer->avatar_url) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                <input type="text" name="gender" required lay-verify="required" placeholder="请输入性别"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('gender',$buyer->gender) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <input type="text" name="remark" required lay-verify="required" placeholder="请输入备注"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('remark',$buyer->remark) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">来源</label>
                            <div class="layui-input-block">
                                <input type="text" name="source" required lay-verify="required" placeholder="请输入来源"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('source',$buyer->source) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">语言</label>
                            <div class="layui-input-block">
                                <input type="text" name="language" required lay-verify="required" placeholder="请输入语言"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('language',$buyer->language) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">国家</label>
                            <div class="layui-input-block">
                                <input type="text" name="country" required lay-verify="required" placeholder="请输入国家"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('country',$buyer->country) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">省</label>
                            <div class="layui-input-block">
                                <input type="text" name="province" required lay-verify="required" placeholder="请输入省"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('province',$buyer->province) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">市</label>
                            <div class="layui-input-block">
                                <input type="text" name="city" required lay-verify="required" placeholder="请输入市"
                                       autocomplete="off" class="layui-input" value="{{ old('city',$buyer->city) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">地址ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="address_id" required lay-verify="required"
                                       placeholder="请输入地址ID" autocomplete="off" class="layui-input"
                                       value="{{ old('address_id',$buyer->address_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入店铺ID"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('shop_id',$buyer->shop_id) }}">
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
                let url = "{{$buyer->id?route('admin.buyers.update',$buyer->id):route('admin.buyers.store')}}";

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

                                window.location.href = "{{ url('admin/buyers/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush