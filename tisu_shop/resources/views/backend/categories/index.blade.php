@extends('backend.layouts.app')

@section('title', $title = '分类列表')

@section('breadcrumb')
    <a>分类管理</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div>
        <h2>{{$title}}</h2>
    </div>
    <hr class="layui-bg-green">
    <div style="padding: 10px;">
        <div class="layui-form" id="queryForm">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <button class="layui-btn layui-btn-normal" id="add"><i class="layui-icon layui-icon-add-1"></i>新增
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="layui-collapse" lay-filter="category_collapse" id="collapse_div" lay-accordion></div>
    </div>
    <style>
        #collapse_div li {
            margin-bottom: 5px;
        }

        #collapse_div li[class="parentLi"] span {
            margin-left: 25px;
            margin-right: 10px;
            font-size: 18px;
        }

        #collapse_div li[class="childLi"] span {
            margin-left: 45px;
            margin-right: 10px;
        }

        #collapse_div li a {
            float: right;
            margin-left: 20px;
        }
        #collapse_div li:hover{
            background-color: #f2f2f2;
        }
    </style>
@endsection

@push('scripts')
    <script>

        //删除 行
        function deleteRow(id, obj) {
            let url = "{{ url('admin/categories/destroy') }}/" + id;
            let _token = "{{csrf_token()}}";

            $.get(url, {"_token": _token}, function (result) {
                let msg = result.message;

                if (!result.success) {
                    layer.open({
                        type: 1,
                        anim: 0,
                        title: msg,
                        area: ['50%', '70%'],
                        btn: ['关闭'],
                        content: JSON.stringify(result)
                    });

                } else {
                    // layer.msg(msg);
                    layer.alert(msg);
                    obj.del(); //删除对应行（tr）的DOM结构
                }
            });
        }


        // 批量删除
        function deleteRows(ids) {

            let url = "{{ url('admin/categories/destroyBat') }}";
            let data = {ids: ids};
            data['_token'] = "{{csrf_token()}}";

            $.ajax({
                type: 'POST',
                url: url,//发送请求
                data: data,
                dataType: "JSON",
                success: function (result) {
                    let msg = result.message;

                    if (!result.success) {
                        layer.open({
                            type: 1,
                            anim: 0,
                            title: msg,
                            area: ['50%', '70%'],
                            btn: ['关闭'],
                            content: JSON.stringify(result)
                        });

                    } else {
                        // layer.msg(msg);
                        layer.alert(msg, function () {
                            window.location.reload();
                        });

                    }

                }
            });
        }

        function getCategoryGroup() {
            let url = "{{route('admin.categories.all')}}";
            let _token = "{{csrf_token()}}";
            $.get(url, {"_token": _token}, function (result) {
                let msg = result.message;

                if (!result.success) {
                    layer.msg(msg);
                } else {
                    //
                    renderCollapse(result.model);
                }
            });
        }

        function renderCollapse(data) {
            let collapseHtml = '';
            data.forEach(function (val, key) {
                let pName = val.name;
                let children = val.children;
                let cHtml = '<ul><li class="parentLi"><span >' + pName + '</span><a class="layui-btn layui-btn-danger layui-btn-xs delete" uqId="' + val.id + '">删除</a>' +
                    '<a class="layui-btn layui-btn-xs edit" href="{{url('admin/categories/edit')}}/' + val.id + '">编辑</a></li>';
                children.forEach(function (v) {
                    cHtml += '<li class="childLi"> <span>-- ' + v.name + '</span><a class="layui-btn layui-btn-danger layui-btn-xs delete" uqId="' + v.id + '">删除</a>' +
                        '<a class="layui-btn layui-btn-xs edit" href="{{url('admin/categories/edit')}}/' + v.id + '">编辑</a></li>';
                });
                cHtml += '</ul>';
                if (key == 0) {
                    collapseHtml += '<div class="layui-colla-item">' +
                        '<h2 class="layui-colla-title">' + pName + '</h2>' +
                        '<div class="layui-colla-content layui-show">' + cHtml + '</div>' +
                        '</div>';
                } else {
                    collapseHtml += '<div class="layui-colla-item">' +
                        '<h2 class="layui-colla-title">' + pName + '</h2>' +
                        '<div class="layui-colla-content">' + cHtml + '</div>' +
                        '</div>';
                }


            })

            $("#collapse_div").html(collapseHtml);

            layui.use(['element', 'layer'], function () {
                var element = layui.element;
                var $ = layui.jquery;
                var layer = layui.layer;

                element.render('collapse', 'category_collapse');
                //监听折叠
                element.on('collapse(category_collapse)', function (data) {
                    // layer.msg('展开状态：' + data.show);
                });

                $('.delete').on('click', function () {
                    let that = $(this);
                    let id = $(this).attr('uqId');
                    let url = "{{ url('admin/categories/destroy') }}/" + id;
                    layer.confirm('确认删除？', function () {
                        let _token = "{{csrf_token()}}";

                        $.get(url, {"_token": _token}, function (result) {
                            let msg = result.message;

                            if (!result.success) {
                                layer.open({
                                    type: 1,
                                    anim: 0,
                                    title: msg,
                                    area: ['50%', '70%'],
                                    btn: ['关闭'],
                                    content: JSON.stringify(result)
                                });

                            } else {
                                layer.msg(msg);
                                that.closest('li').remove();
                            }
                        });
                    })


                })

            });
        }

        getCategoryGroup();

        $("#add").on('click', function () {
            window.location.href = "{{ route('admin.categories.create') }}";
        })

    </script>
@endpush