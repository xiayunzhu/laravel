@extends('backend.layouts.app')

@section('title', $title = $goods->id ? '详情' : '--' )

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
                    <pre>
                         {{print_r($goods->toArray(),true)}}
                    </pre>
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