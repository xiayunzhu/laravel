@extends('backend.layouts.plain')
@section('title', '选择可配送区域' )


@section('content')

    <div class="layui-col-md12">

        <div class="layui-layer-content" style="padding: 1px 10px;">
            <div style="padding: 50px 30px;">
                <div class="regional-choice layui-layer-wrap" style="display: block;">
                <div class="place-div">
                    <div>
                        <div class="checkbtn">
                            <label>
                                <input id="allCheck" type="checkbox">
                                全选</label>

                        </div>
                        <div class="place clearfloat">
                            <div class="smallplace clearfloat">
                                @if(!empty($address))
                                    @foreach($address as $val=>$province)
                                        {{--省级开始--}}
                                        <div class="place-tooltips">
                                            <label>
                                                <input type="checkbox" name="addressIds" class="province" value="{{$province['id']}}">
                                                <span class="province_name" id="span_{{$province['id']}}" data-pid="{{$province['pid']}}">{{$province['name']}}</span>
                                                <span class="ratio"></span>
                                            </label>

                                            <div class="citys">
                                                <i class="jt"><i></i></i>
                                                <div class="row-div clearfloat">
                                                    @if(!empty($province['citys']))
                                                        @foreach($province['citys'] as $v=>$citys)
                                                        {{--城市开始--}}
                                                            <p>
                                                                <label>
                                                                    <input type="checkbox" name="addressIds" class="city" value="{{$citys['id']}}">
                                                                    <span id="span_{{$citys['id']}}" data-pid="{{$citys['pid']}}">{{$citys['name']}}</span>
                                                                </label>
                                                            </p>
                                                        {{--城市结束--}}
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        {{--省级结束--}}
                                    @endforeach
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    </div>
    <style>
        *, :after, :before {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .layui-layer-page .layui-layer-content {
            position: relative;
            overflow: auto;
        }
        .layui-layer-content .regional-choice {
            display: block !important;
        }
        .regional-choice {
            /*display: none;*/
            user-select: none;
        }
        .clearfloat {
            zoom: 1;
        }
        .place-div .smallplace > div {
            float: left;
            width: 170px;
            margin: 0;
            padding-bottom: 10px;
            padding-top: 5px;
            position: relative;
        }
        .place-div .smallplace label {
            padding-right: 10px;
            text-align: left;
            width: auto;
            float: left;
            cursor: pointer;
        }
        .place-div .smallplace .citys {
            width: auto;
            background-color: #fff;
            position: absolute;
            top: 35px;
            border: 1px solid #ccc;
            z-index: 100;
            visibility: hidden;
        }
        .place-div .smallplace .citys > i.jt {
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 10px solid #ccc;
            position: absolute;
            top: -10px;
            left: 20px;
        }
        .place-div .smallplace .citys .row-div {
            min-width: 250px;
            padding: 10px;
            box-sizing: border-box;
        }
        .place-div .smallplace p {
            float: left;
            width: auto;
            margin: 2px 0;
        }
        .place-div .smallplace label {
            padding-right: 10px;
            text-align: left;
            width: auto;
            float: left;
            cursor: pointer;
        }
        .place-div input[type=checkbox] {
            margin-right: .3rem;
        }

    </style>

@endsection
@push('scripts')
<script>

    $(function() {

        // 鼠标移入移出  城市显示隐藏
        $(".place-tooltips").mouseover(function(){
            $(this).find(".citys").css("visibility","visible");
        });
        $(".place-tooltips").mouseout(function(){
            $(this).find(".citys").css("visibility","hidden");

        })

        // 全选反选
        $("#allCheck").on('click', function() {
            $(".place-tooltips input:checkbox").prop("checked", $(this).prop('checked'));
        })
        $(".place-tooltips input:checkbox").on('click', function() {
            //当选中的长度等于checkbox的长度的时候,就让控制全选反选的checkbox设置为选中,否则就为未选中
            if($("tbody input:checkbox").length === $("tbody input:checked").length) {
                $("#allCheck").prop("checked", true);
            } else {
                $("#allCheck").prop("checked", false);
            }
        })
        // 城市全选反选
        $('.province').on('click', function() {
            $(this).parent().next().find("input:checkbox").prop("checked", $(this).prop('checked'));
        })

        $(".citys input:checkbox").on('click', function() {
            let citys = $(this).parents('.citys');
            //当选中的长度等于checkbox的长度的时候,就让控制全选反选的checkbox设置为选中,否则就为未选中
            if($(citys).find("input:checkbox").length > $(citys).find("input:checked").length) {

                $(citys).prev().find('.province').prop("checked", true);
            }
        })

    })
</script>
@endpush