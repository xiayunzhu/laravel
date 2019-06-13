@extends('backend.layouts.app')

@section('title', $title = $product->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>基础资料</a>
    <a href="{{ route('admin.products') }}">资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2 layui-col-lg6 layui-col-lg-offset3">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                             <div class="layui-form-item">
                    <label class="layui-form-label">货号</label>
                    <div class="layui-input-block">
                        <input type="text" name="article_number" required lay-verify="required" placeholder="请输入货号" autocomplete="off" class="layui-input" value="{{ old('article_number',$product->article_number) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">商品条码</label>
                    <div class="layui-input-block">
                        <input type="text" name="bar_code" required lay-verify="required" placeholder="请输入商品条码" autocomplete="off" class="layui-input" value="{{ old('bar_code',$product->bar_code) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">品牌ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="brand_id" required lay-verify="required" placeholder="请输入品牌ID" autocomplete="off" class="layui-input" value="{{ old('brand_id',$product->brand_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">类目ID</label>
                    <div class="layui-input-block">
                        <input type="text" name="catagory_id" required lay-verify="required" placeholder="请输入类目ID" autocomplete="off" class="layui-input" value="{{ old('catagory_id',$product->catagory_id) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">颜色</label>
                    <div class="layui-input-block">
                        <input type="text" name="color" required lay-verify="required" placeholder="请输入颜色" autocomplete="off" class="layui-input" value="{{ old('color',$product->color) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">商品编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="item_code" required lay-verify="required" placeholder="请输入商品编码" autocomplete="off" class="layui-input" value="{{ old('item_code',$product->item_code) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">商品名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="item_name" required lay-verify="required" placeholder="请输入商品名称" autocomplete="off" class="layui-input" value="{{ old('item_name',$product->item_name) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">其他规格</label>
                    <div class="layui-input-block">
                        <input type="text" name="other_prop" required lay-verify="required" placeholder="请输入其他规格" autocomplete="off" class="layui-input" value="{{ old('other_prop',$product->other_prop) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">标价</label>
                    <div class="layui-input-block">
                        <input type="text" name="price" required lay-verify="required" placeholder="请输入标价" autocomplete="off" class="layui-input" value="{{ old('price',$product->price) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">规格编码</label>
                    <div class="layui-input-block">
                        <input type="text" name="spec_code" required lay-verify="required" placeholder="请输入规格编码" autocomplete="off" class="layui-input" value="{{ old('spec_code',$product->spec_code) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-block">
                        <input type="text" name="status" required lay-verify="required" placeholder="请输入状态" autocomplete="off" class="layui-input" value="{{ old('status',$product->status) }}">
                    </div>
                </div>             <div class="layui-form-item">
                    <label class="layui-form-label">单位</label>
                    <div class="layui-input-block">
                        <input type="text" name="unit" required lay-verify="required" placeholder="请输入单位" autocomplete="off" class="layui-input" value="{{ old('unit',$product->unit) }}">
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
                let url = "{{$product->id?route('admin.products.update',$product->id):route('admin.products.store')}}";

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

                                window.location.href = "{{ url('admin/categories/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush