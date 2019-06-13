@extends('backend.layouts.app')

@section('title', $title = $region->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>区域管理</a>
    <a href="{{ route('admin.regions') }}">区域列表</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">上级ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="pid" required lay-verify="required" placeholder="请输入上级ID"
                               autocomplete="off" class="layui-input" value="{{ old('pid',$region->pid) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">简称</label>
                    <div class="layui-input-block">
                        <input type="text" name="shortname" required lay-verify="required" placeholder="请输入简称"
                               autocomplete="off" class="layui-input" value="{{ old('shortname',$region->shortname) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入名称"
                               autocomplete="off" class="layui-input" value="{{ old('name',$region->name) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="merger_name" required lay-verify="required" placeholder="请输入名称"
                               autocomplete="off" class="layui-input"
                               value="{{ old('merger_name',$region->merger_name) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">级别</label>
                    <div class="layui-input-block">
                        <input type="text" name="level" required lay-verify="required" placeholder="请输入级别"
                               autocomplete="off" class="layui-input" value="{{ old('level',$region->level) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">拼音</label>
                    <div class="layui-input-block">
                        <input type="text" name="pinyin" required lay-verify="required" placeholder="请输入拼音"
                               autocomplete="off" class="layui-input" value="{{ old('pinyin',$region->pinyin) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">区域编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="code" required lay-verify="required" placeholder="请输入区域编码"
                               autocomplete="off" class="layui-input" value="{{ old('code',$region->code) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮政编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="zip_code" required lay-verify="required" placeholder="请输入邮政编码"
                               autocomplete="off" class="layui-input" value="{{ old('zip_code',$region->zip_code) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">首字母</label>
                    <div class="layui-input-block">
                        <input type="text" name="first" required lay-verify="required" placeholder="请输入首字母"
                               autocomplete="off" class="layui-input" value="{{ old('first',$region->first) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">经度</label>
                    <div class="layui-input-block">
                        <input type="text" name="lng" required lay-verify="required" placeholder="请输入经度"
                               autocomplete="off" class="layui-input" value="{{ old('lng',$region->lng) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">纬度</label>
                    <div class="layui-input-block">
                        <input type="text" name="lat" required lay-verify="required" placeholder="请输入纬度"
                               autocomplete="off" class="layui-input" value="{{ old('lat',$region->lat) }}">
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

    </div>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use('form', function () {
            let form = layui.form;

            //监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$region->id?route('admin.regions.update',$region->id):route('admin.regions.store')}}";

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

                                window.location.href = "{{ url('admin/regions/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush