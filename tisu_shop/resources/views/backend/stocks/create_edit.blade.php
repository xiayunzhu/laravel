@extends('backend.layouts.app')

@section('title', $title = $stock->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>仓库库存</a>
    <a href="{{ route('admin.stocks') }}">SKU库存</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">规格编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="sku_code" required lay-verify="required" placeholder="请输入系统规格编码"
                               autocomplete="off" class="layui-input" value="{{ old('sku_code',$stock->sku_code) }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">实际库存</label>
                    <div class="layui-input-block">
                        <input type="text" name="quantity" required lay-verify="required" placeholder="请输入实际库存"
                               autocomplete="off" class="layui-input" value="{{ old('quantity',$stock->quantity) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">可用库存</label>
                    <div class="layui-input-block">
                        <input type="text" name="available" required lay-verify="required" placeholder="请输入可用库存"
                               autocomplete="off" class="layui-input" value="{{ old('available',$stock->available) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="item_code" required lay-verify="required" placeholder="请输入商品编码"
                               autocomplete="off" class="layui-input" value="{{ old('item_code',$stock->item_code) }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">修改时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="modified" required lay-verify="required" placeholder="请输入库存修改时间"
                               autocomplete="off" class="layui-input" value="{{ old('modified',$stock->modified) }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">仓库编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="storage_code" required lay-verify="required" placeholder="请输入仓库编码"
                               autocomplete="off" class="layui-input"
                               value="{{ old('storage_code',$stock->storage_code) }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">仓库名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="storage_name" required lay-verify="required" placeholder="请输入仓库名称"
                               autocomplete="off" class="layui-input"
                               value="{{ old('storage_name',$stock->storage_name) }}" disabled>
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
                let url = "{{$stock->id?route('admin.stocks.update',$stock->id):route('admin.stocks.store')}}";

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

                                window.location.href = "{{ url('admin/stocks/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush