@extends('backend.layouts.app')

@section('title', $title = $goods->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>售卖商品</a>
    <a href="{{ route('admin.goods') }}">商品</a>
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
                            <label class="layui-form-label">品牌ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="brand_id" required lay-verify="required" placeholder="请输入品牌ID"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('brand_id',$goods->brand_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">类目ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="category_id" required lay-verify="required"
                                       placeholder="请输入类目ID" autocomplete="off" class="layui-input"
                                       value="{{ old('category_id',$goods->category_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品详情</label>
                            <div class="layui-input-block">
                                <input type="text" name="content" required lay-verify="required" placeholder="请输入商品详情"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('content',$goods->content) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">扣减库存的方式</label>
                            <div class="layui-input-block">
                                <input type="text" name="deduct_stock_type" required lay-verify="required"
                                       placeholder="请输入扣减库存的方式" autocomplete="off" class="layui-input"
                                       value="{{ old('deduct_stock_type',$goods->deduct_stock_type) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">运费模版</label>
                            <div class="layui-input-block">
                                <input type="text" name="delivery_id" required lay-verify="required"
                                       placeholder="请输入运费模版" autocomplete="off" class="layui-input"
                                       value="{{ old('delivery_id',$goods->delivery_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品排序</label>
                            <div class="layui-input-block">
                                <input type="text" name="goods_sort" required lay-verify="required"
                                       placeholder="请输入商品排序" autocomplete="off" class="layui-input"
                                       value="{{ old('goods_sort',$goods->goods_sort) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品简介</label>
                            <div class="layui-input-block">
                                <input type="text" name="introduction" required lay-verify="required"
                                       placeholder="请输入商品简介" autocomplete="off" class="layui-input"
                                       value="{{ old('introduction',$goods->introduction) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" required lay-verify="required" placeholder="请输入商品名称"
                                       autocomplete="off" class="layui-input" value="{{ old('name',$goods->name) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">发布状态</label>
                            <div class="layui-input-block">
                                <input type="text" name="publish_status" required lay-verify="required"
                                       placeholder="请输入发布状态 - 0:下架,1:上架" autocomplete="off" class="layui-input"
                                       value="{{ old('publish_status',$goods->publish_status) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">实际销售</label>
                            <div class="layui-input-block">
                                <input type="text" name="sales_actual" required lay-verify="required"
                                       placeholder="请输入实际销售" autocomplete="off" class="layui-input"
                                       value="{{ old('sales_actual',$goods->sales_actual) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">初始销量</label>
                            <div class="layui-input-block">
                                <input type="text" name="sales_initial" required lay-verify="required"
                                       placeholder="请输入初始销量" autocomplete="off" class="layui-input"
                                       value="{{ old('sales_initial',$goods->sales_initial) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品状态</label>
                            <div class="layui-input-block">
                                <input type="text" name="sales_status" required lay-verify="required"
                                       placeholder="请输入商品状态 - SOLD_OUT:售罄,ON_SALE:在售, PRE_SALE:预售" autocomplete="off"
                                       class="layui-input" value="{{ old('sales_status',$goods->sales_status) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">店铺ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="shop_id" required lay-verify="required" placeholder="请输入店铺ID"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('shop_id',$goods->shop_id) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">规格类型</label>
                            <div class="layui-input-block">
                                <input type="text" name="spec_type" required lay-verify="required" placeholder="请输入规格类型"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('spec_type',$goods->spec_type) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" required lay-verify="required" placeholder="请输入商品标题"
                                       autocomplete="off" class="layui-input" value="{{ old('title',$goods->title) }}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">版本号</label>
                            <div class="layui-input-block">
                                <input type="text" name="version" required lay-verify="required" placeholder="请输入版本号"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('version',$goods->version) }}">
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
                let url = "{{$goods->id?route('admin.goods.update',$goods->id):route('admin.goods.store')}}";

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

                                window.location.href = "{{ url('admin/goods/edit') }}/" + result.model.id;

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush