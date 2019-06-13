@extends('backend.layouts.app')

@section('title', $title = '吐槽反馈')

@section('breadcrumb')

    <a>基础资料</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')

    <div>
        <h2>{{$title}}</h2>
    </div>
    <hr class="layui-bg-green">
    <div style="padding: 10px;">
        <div class="layui-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-block">
                            <input type="text" class="layui-input" readonly="readonly" value="{{$teasing->title}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <label class="layui-form-label">内容</label>
                        <div class="layui-input-block">
                            <textarea readonly="readonly">{{$teasing->content}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图片</label>
                <div class="layui-input-block">
                    @foreach($imgs as $img)
                        <img src="{{$img->img_url}}" style="height: 200px;">
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>

        //删除 行

    </script>
@endpush