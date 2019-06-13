@extends('backend.layouts.app')

@section('title', $title = $orgGood->id ? '编辑' : '添加' )

@section('breadcrumb')
    <a>原始商品管理</a>
    <a href="{{ route('admin.orgGoods') }}">商品</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    @php
        $key = 0;
        $categoryOptions = App\Handlers\CategoryHandler::options();
        $brandOptions = App\Handlers\BrandsHandler::brands();
        $key_label_values = 0;
    @endphp
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md10 layui-col-md-offset1 layui-col-lg10 layui-col-lg-offset1 site-block">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <blockquote class="layui-elem-quote">基本信息</blockquote>
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品名称<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <input type="hidden" name="id" value="{{ old('id',$orgGood->id) }}">
                            <input type="text" name="name" required lay-verify="required" placeholder="请输入商品名称"
                                   autocomplete="off" class="layui-input" value="{{ old('name',$orgGood->name) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品标题<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required lay-verify="required" placeholder="请输入商品标题"
                                   autocomplete="off" class="layui-input" value="{{ old('title',$orgGood->title) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">
                            <a title="点击新增分类" id="add_category">
                                <i class="layui-icon layui-icon-add-1"></i>分类
                            </a>
                            <i style="color: red">*</i>
                        </label>
                        <div class="layui-input-block">
                            <select id="category" lay-verify="required" name="category_id">
                                <option value="">请选择品类</option>
                                @if(!empty($categoryOptions))
                                    @foreach($categoryOptions as $option)
                                        @if($option['parent_id']==0)
                                            <optgroup label="{{$option['name']}}"></optgroup>
                                        @endif
                                        <option value="{{$option['id']}}"
                                                @if($option['id']==old('category_id',$orgGood->category_id)) selected @endif>{{$option['name']}}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">LOGO<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary choosePic"
                                    param="{{\App\Models\OrgGoodImage::PROPERTY_LOGO}}">
                                <i class="layui-icon layui-icon-upload-drag"></i>选择图片
                            </button>
                            <div class="uploader-list am-cf" id="uploader-list">

                                @if($orgGood->images)
                                    @foreach($orgGood->images as $k=>$row)
                                        @if($row['property'] == \App\Models\OrgGoodImage::PROPERTY_LOGO)
                                            <div class="file-item">
                                                <a href="{{storage_image_url($row['file_url'])}}" title="点击查看大图"
                                                   target="_blank">
                                                    <img src="{{storage_image_url($row['file_url'])}}">
                                                </a>
                                                <input type="hidden"
                                                       name="images[{{\App\Models\OrgGoodImage::PROPERTY_LOGO}}][]"
                                                       value="{{$row['image_id']}}">
                                                <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品图片<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary choosePic"
                                    param="{{\App\Models\OrgGoodImage::PROPERTY_MAIN}}">
                                <i class="layui-icon layui-icon-upload-drag"></i>选择图片
                            </button>
                            <div class="uploader-list am-cf" id="uploader-list">

                                @if($orgGood->images)
                                    @foreach($orgGood->images as $k=>$row)
                                        @if($row['property'] == \App\Models\OrgGoodImage::PROPERTY_MAIN)
                                            <div class="file-item">
                                                <a href="{{storage_image_url($row['file_url'])}}" title="点击查看大图"
                                                   target="_blank">
                                                    <img src="{{storage_image_url($row['file_url'])}}">
                                                </a>
                                                <input type="hidden"
                                                       name="images[{{\App\Models\OrgGoodImage::PROPERTY_MAIN}}][]"
                                                       value="{{$row['image_id']}}">
                                                <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item" style="width: 1500px">
                        <div class="layui-inline">
                            <label class="layui-form-label" style="width: 100px;">佣金比例(%)<i
                                        style="color: red">*</i></label>
                            <div class="layui-input-inline">
                                <input type="text" name="commission_rate" required lay-verify="required"
                                       placeholder="请输入佣金比例(%)"
                                       autocomplete="off" class="layui-input"
                                       value="{{ old('commission_rate',$orgGood->commission_rate) }}">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">品牌</label>
                            <div class="layui-input-inline">
                                <select required lay-verify="required" name="brand_id">
                                    <option value="0">请选择品牌</option>
                                    @if(!empty($brandOptions))
                                        @foreach($brandOptions as $k=>$val)
                                            <option value="{{$k}}"
                                                    @if($k==old('brand_id',$orgGood->brand_id)) selected @endif>{{$val}}</option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品标签</label>
                        <div class="layui-input-block">
                            <div class="spec-item-add am-cf am-fl">

                                @if($orgGood->lables)
                                    @foreach($orgGood->lables as $key_label_values=>$row)
                                        <input style="margin-left: 10px" type="text"
                                               class="ipt-specItem am-fl am-field-valid"
                                               value="{{$row['label_value']}}"
                                               name="label_values[{{$key_label_values}}]" flag="{{$key_label_values}}">
                                        <button type="button" class="am-btn am-fl am-btn-del ">删除</button>
                                    @endforeach
                                @endif
                                <input style="margin-left: 10px" type="text" class="ipt-specItem am-fl am-field-valid"
                                       name="label_values[{{$key_label_values + 1}}]" flag="{{$key_label_values + 1}}">
                                <button type="button" class="am-btn am-fl am-btn-add ">添加</button>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品简介</label>
                        <div class="layui-input-block">
                            <input type="text" name="introduction" placeholder="请输入商品简介"
                                   autocomplete="off" class="layui-input"
                                   value="{{ old('introduction',$orgGood->introduction) }}">
                        </div>
                    </div>
                </div>
                <blockquote class="layui-elem-quote">规格/库存</blockquote>
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品规格<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary layui-btn-sm addProduct"
                                    style="margin-left: 20px;margin-top: 30px">添加规格+
                            </button>
                            <div class="layui-field-box" style="width: 1500px;overflow:auto">
                                <table class="layui-table" id="tbl_bj" style="width: 1450px">
                                    <colgroup>
                                        <col width="60">
                                        <col width="160">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="111">
                                        <col width="100">
                                        <col width="80">
                                        <col width="90">
                                        <col width="90">
                                        <col width="90">
                                        <col width="90">
                                        <col width="90">

                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th width="60px" style="width: 60px">操作</th>
                                        <th>规格图片</th>
                                        <th>商品编号</th>
                                        <th>商品价格</th>
                                        <th>划线价格</th>
                                        <th>建议零售价</th>
                                        <th>颜色</th>
                                        <th>尺码</th>
                                        <th>虚拟库存</th>
                                        <th>虚拟销量</th>
                                        <th>商品条码</th>
                                        {{--<th>商品重量KG</th>--}}
                                        <th>规格名称</th>
                                        <th>发布状态</th>
                                        <th>ERP规格编码</th>

                                    </tr>
                                    </thead>
                                    <tbody>


                                    @if($orgGood->specs)
                                        @foreach($orgGood->specs as $key=>$row)

                                            <tr id="{{$key}}">
                                                <td width="50" style="width: 50px">
                                                    <div class="layui-btn-group">
                                                        <button value="{{$row['id']}}"
                                                                class="layui-btn layui-btn-primary deleteRow">
                                                            <i class="layui-icon layui-icon-delete"></i></button>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="layui-btn layui-btn-primary choosePic"
                                                            param="spec_img" num="{{$key+1}}"
                                                            @if(!empty($row['image_url'])) style="display: none" @endif>
                                                        <i class="layui-icon layui-icon-upload-drag"></i>选择图片
                                                    </button>
                                                    <div class="uploader-list am-cf" id="uploader-list">
                                                        @if(!empty($row['image_url']))
                                                            <div class="file-item">
                                                                <a href="{{ storage_image_url($row['image_url']) }}"
                                                                   title="点击查看大图" target="_blank">
                                                                    <img src="{{ storage_image_url($row['image_url']) }}">
                                                                </a>
                                                                <input type="hidden" name="specs[{{$key+1}}][image_url]"
                                                                       value="{{$row['image_url']}}">
                                                                <i class="layui-icon layui-icon-close"
                                                                   onclick="delImg(this)"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="specs[{{$key+1}}][id]"
                                                           value="{{!empty($row['id'])?$row['id']:''}}">
                                                    <input type="text" name="specs[{{$key+1}}][org_goods_no]"
                                                           class="layui-input"
                                                           value="{{!empty($row['org_goods_no'])?$row['org_goods_no']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][org_goods_price]"
                                                           class="layui-input"
                                                           value="{{!empty($row['org_goods_price'])?$row['org_goods_price']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][line_price]"
                                                           class="layui-input"
                                                           value="{{!empty($row['line_price'])?$row['line_price']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][retail_price]"
                                                           class="layui-input"
                                                           value="{{!empty($row['retail_price'])?$row['retail_price']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][color]"
                                                           required lay-verify="required"
                                                           class="layui-input"
                                                           value="{{!empty($row['color'])?$row['color']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][size]"
                                                           required lay-verify="required"
                                                           class="layui-input"
                                                           value="{{!empty($row['size'])?$row['size']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][virtual_quantity]"
                                                           class="layui-input" required lay-verify="required"
                                                           value="{{!empty($row['virtual_quantity'])?$row['virtual_quantity']:''}}">
                                                    <input type="hidden" name="specs[{{$key+1}}][quantity_offset]"
                                                           class="layui-input" required lay-verify="required"
                                                           value="{{!empty($row['quantity_offset'])?$row['quantity_offset']:''}}">
                                                </td>

                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][virtual_sold_num]"
                                                           required lay-verify="required"
                                                           class="layui-input"
                                                           value="{{!empty($row['virtual_sold_num'])?$row['virtual_sold_num']:''}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][barcode]"
                                                           class="layui-input"
                                                           value="{{!empty($row['barcode'])?$row['barcode']:''}}">
                                                </td>

                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][spec_name]"
                                                           class="layui-input"
                                                           value="{{!empty($row['spec_name'])?$row['spec_name']:''}}">
                                                </td>
                                                <td>
                                                    <button class="layui-btn layui-btn-sm"
                                                            onclick="publish_status(this)">
                                                        @if($row['publish_status'] == \App\Models\OrgGood::PUBLISH_STATUS_LOWER)
                                                            下架@else上架@endif
                                                    </button>
                                                    <input type="hidden" name="specs[{{$key+1}}][publish_status]"
                                                           class="layui-input" id="publish_status0" hidden
                                                           value="{{$row['publish_status']}}">
                                                </td>
                                                <td>
                                                    <input type="text" name="specs[{{$key+1}}][spec_code]"
                                                           class="layui-input"
                                                           value="{{!empty($row['spec_code'])?$row['spec_code']:''}}">
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">库存计算方式<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <input type="radio" name="deduct_stock_type" value="1" title="下单减库存"
                                   lay-filter="deduct_stock_type"
                                   @if(old('deduct_stock_type',$orgGood->deduct_stock_type) == 1) checked="checked" @endif>
                            <input type="radio" name="deduct_stock_type" value="2" title="支付减库存"
                                   lay-filter="deduct_stock_type"
                                   @if(old('deduct_stock_type',$orgGood->deduct_stock_type) == 2) checked="checked" @endif>
                        </div>
                    </div>
                </div>

                <blockquote class="layui-elem-quote">商品详情</blockquote>
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">详情图片</label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary choosePic"
                                    param="{{\App\Models\OrgGoodImage::PROPERTY_DETAIL}}">
                                <i class="layui-icon layui-icon-upload-drag"></i>选择图片
                            </button>
                            <div class="uploader-list am-cf" id="uploader-list">

                                @if($orgGood->images)
                                    @foreach($orgGood->images as $k=>$row)
                                        @if($row['property'] == \App\Models\OrgGoodImage::PROPERTY_DETAIL)
                                            <div class="file-item">
                                                <a href="{{storage_image_url($row['file_url'])}}" title="点击查看大图"
                                                   target="_blank">
                                                    <img src="{{storage_image_url($row['file_url'])}}">
                                                </a>
                                                <input type="hidden"
                                                       name="images[{{\App\Models\OrgGoodImage::PROPERTY_DETAIL}}][]"
                                                       value="{{$row['image_id']}}">
                                                <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品参数</label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-primary layui-btn-sm" id="addParam">添加参数配置+</button>
                            <table class="layui-table" id="tbl_param">
                                <colgroup>
                                    <col width="60">
                                    <col width="500">
                                    <col width="40">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>规格</th>
                                    <th>属性值</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($orgGood->detail_specs)
                                    @foreach($orgGood->detail_specs as $goodKey=>$row)
                                        <tr>
                                            <td>
                                                <select lay-filter="param">
                                                    @if($specs)
                                                        @foreach($specs as $k=>$r)
                                                            <option value="{{$r['id']}}"
                                                                    @if($r['id'] == $row['spec_id']))
                                                                    selected @endif>{{$r['spec_name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </td>
                                            <td class="specsCheckbox">
                                                <select name="specs_values[]">
                                                    @if($row['all'])
                                                        @foreach($row['all']['specValues'] as $kk=>$rr)
                                                            <option value="{{$rr['id']}}"
                                                                    @if($rr['id'] == $row['spec_value_id']))
                                                                    selected @endif>{{$rr['spec_value']}}</option>
                                                        @endforeach
                                                        <option value="">请选择</option>

                                                    @endif
                                                </select>

                                            </td>
                                            <td>
                                                <button class="layui-btn layui-btn-primary layui-btn-sm delParam"><i
                                                            class="layui-icon layui-icon-delete"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">富文本</label>
                        <div class="layui-input-block">
                            <textarea id="graphicMixed" name="content" style="display: none;" lay-verify="content">
                                {{ old('content',$orgGood->content) }}
                            </textarea>
                        </div>
                    </div>
                </div>


                <blockquote class="layui-elem-quote">其他设置</blockquote>
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">运费模版</label>
                        <div class="layui-input-inline">
                            <select  id="delivery" name="delivery_id" lay-search>
                                <option value="">请选择运费模版</option>
                                @if(!empty($deliveries))
                                    @foreach($deliveries as $val)
                                        <option value="{{$val['id']}}"
                                                @if($val['id']==old('delivery_id',$orgGood->delivery_id)) selected @endif>{{$val['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <button class="layui-btn layui-btn-primary" onclick="refreshFreight()"><i
                                        class="layui-icon layui-icon-refresh"></i>刷新模版
                            </button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品状态 <i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <input type="radio" name="sales_status" value="sold_out" title="售罄"
                                   lay-filter="sales_status"
                                   @if(old('sales_status',$orgGood->sales_status) == \App\Models\OrgGood::SALE_STATUS_SOLD_OUT) checked="checked" @endif>
                            <input type="radio" name="sales_status" value="on_sale" title="在售" lay-filter="sales_status"
                                   @if(old('sales_status',$orgGood->sales_status) == \App\Models\OrgGood::SALE_STATUS_ON_SALE) checked="checked" @endif>
                            <input type="radio" name="sales_status" value="pre_sale" title="预售"
                                   lay-filter="sales_status"
                                   @if(old('sales_status',$orgGood->sales_status) == \App\Models\OrgGood::SALE_STATUS_PRE_SALE) checked="checked" @endif>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">发布状态<i style="color: red">*</i></label>
                        <div class="layui-input-block">
                            <input type="radio" name="publish_status"
                                   value="{{\App\Models\OrgGood::PUBLISH_STATUS_UPPER}}" title="上架"
                                   lay-filter="publish_status"
                                   @if(old('publish_status',$orgGood->publish_status) == \App\Models\OrgGood::PUBLISH_STATUS_UPPER ) checked="checked" @endif>
                            <input type="radio" name="publish_status"
                                   value="{{\App\Models\OrgGood::PUBLISH_STATUS_LOWER}}" title="下架"
                                   lay-filter="publish_status"
                                   @if(old('publish_status',$orgGood->publish_status) == \App\Models\OrgGood::PUBLISH_STATUS_LOWER ) checked="checked" @endif>

                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">初始销量</label>
                        <div class="layui-input-block">
                            <input type="number" name="sales_initial"
                                   placeholder="请输入初始销量" required lay-verify="required"
                                   autocomplete="off" class="layui-input"
                                   value="{{ old('sales_initial',$orgGood->sales_initial) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">实际销售</label>
                        <div class="layui-input-block">
                            <input type="number" name="sales_actual"
                                   placeholder="请输入实际销售" required lay-verify="required"
                                   autocomplete="off" class="layui-input"
                                   value="{{ old('sales_actual',$orgGood->sales_actual) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品排序</label>
                        <div class="layui-input-block">
                            <input type="number" name="goods_sort" required lay-verify="required" placeholder="请输入商品排序"
                                   autocomplete="off" class="layui-input"
                                   value="{{ old('goods_sort',$orgGood->goods_sort) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">当前版本号<i style="color: red">*</i></label>
                        <div class="layui-input-inline">
                            <input id="version" type="text" name="version" readonly required lay-verify="required"
                                   placeholder="请输入版本号"
                                   autocomplete="off" class="layui-input"

                                   value="@if($orgGood->id) {{$orgGood->version}} @else 1 @endif">
                        </div>
                        <div class="layui-input-inline">
                            <button class="layui-btn layui-btn-primary  @if(!$orgGood->id) layui-btn-disabled @endif"
                                    @if(!$orgGood->id) disabled @endif  onclick="upVersion()"><i
                                        class="layui-icon layui-icon-upload-circle"></i>升级版本
                            </button>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/html" id="bartblProduct">
            <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="choose">选择商品</a></script>
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm" id="getCheckData" lay-event="getCheckData">选好了</button>
            </div>
        </script>
    </div>
    <style>
        .layui-elem-quote {
            border-left: 5px solid #393d49;
            background-color: #fff;
            border-bottom: 1px solid #cccccc;

        }

        .layui-input {
            border: 0px;
            border-bottom: 1px solid #cccccc;
        }

        .uploader-list .file-item {
            float: left;
            min-width: 110px;
            position: relative;
            margin: 20px 25px 0 0;
            padding: 4px;
            border: 1px solid #ddd;
            background: #fff;
        }

        .file-item img {
            width: 108px;
            height: 108px;
        }

        .layui-icon-close {
            position: absolute;
            top: -10px;
            right: -10px;
            cursor: pointer;
            height: 22px;
            width: 22px;
            line-height: 22px;
            background: rgba(153, 153, 153, 0.7);
            border-radius: 50%;
            text-align: center;
            color: #fff !important;
            display: block;
        }

        .am-fl {
            float: left;
        }

        .spec-item-add input {
            width: 110px;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;

            padding: 5px 5px;
            line-height: 1.44;
            -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            border: 1px solid #c2cad8;
            color: #555;
            box-shadow: none;
        }

        .am-btn {
            margin-bottom: 0;
            padding: .5em 1em;
            vertical-align: middle;
            font-weight: 400;
            line-height: 1.2;
            text-align: center;
            white-space: nowrap;
            background-image: none;
            border: 1px solid transparent;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;

            cursor: pointer;
            -webkit-appearance: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-transition: background-color .3s ease-out, border-color .3s ease-out;
            transition: background-color .3s ease-out, border-color .3s ease-out;
            color: #5e5e5e;
        }

    </style>
@endsection


@push('scripts')
    {{--<script src="{{asset('js/wangEditor/wangEditor.min.js')}}"></script>--}}
    <script>


        //获取url中的参数
        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]);
            return null; //返回参数值
        }

        let toCopy = getUrlParam('toCopy');


        $(document).on('click', '.delParam', function () {

            let objTr = $(this).closest('tr');
            layer.confirm('真的删除行么', function (index) {
                objTr.remove();
                layer.close(index);
            });
        });

        layui.use(['form', 'layedit'], function () {
            let form = layui.form;
            var tab = 0;
            var i = 0;
            tmp = "{{$orgGood->id}}";
            if (tmp != '') {
                i = "{{$key+2}}";
            }


            var layedit = layui.layedit;

            let _token = "{{csrf_token()}}";
            let upload_url = '{{ route('admin.uploadFiles.uploader') }}?isRichText=1&file_type=image&folder=pics&_token=' + _token;
            layedit.set({
                uploadImage: {
                    url: upload_url //接口url
                    , type: 'post' //默认post
                }
            });
            let index = layedit.build('graphicMixed'); //建立编辑器
            //监听提交
            form.verify({
                content: function (value) {
                    return layedit.sync(index);
                }
            })

            bindProduct();
            bindDeleteRow();


            // 规格选择 单规格0||多规格1
            form.on('radio(spec_type)', function (data) {

                tab = data.value;
                if (tab == 1) {
                    $('#spec_tab1').css("display", "block");
                    $('#spec_tab0').css("display", "none");

                } else {
                    $('#spec_tab1').css("display", "none");
                    $('#spec_tab0').css("display", "block");
                }
            });
            // 监听提交
            form.on('submit(formCommit)', function (data) {
                let url = "{{$orgGood->id?route('admin.orgGoods.update',$orgGood->id):route('admin.orgGoods.store')}}";
                if (toCopy !== null) {
                    url = "{{route('admin.orgGoods.store')}}";
                }
                info = data.field;
                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: info,
                    dataType: "JSON",
                    success: function (result) {
                        console.log(result);
                        let msg = result.message;

                        if (!result.success) {
                            layer.msg(msg);
                        } else {
                            layer.alert(msg, function (index) {
                                layer.close(index);
                                if (toCopy == null) {
                                    window.location.href = "{{ url("admin/orgGoods/edit")}}/"+ result.model.id;
                                    return;
                                }
                                window.location.href = "{{ url("admin/orgGoods")}}";
                            });
                        }
                    }
                });


            });
            // 监听商品参数规格选择
            form.on('select(param)', function (data) {
                let tr = $(this).parents('tr');
                let spec_id = data.value;
                let _token = "{{csrf_token()}}";
                let url = "{{route('admin.orgGoods.specs')}}";

                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: {"_token": _token, 'spec_id': spec_id},
                    dataType: "JSON",
                    success: function (result) {

                        $(tr).find('.specsCheckbox').html(result.model);
                        layui.form.render(); // 重新渲染
                    },
                    error: function () {
                    }
                });


            })


            // 删除规格行
            function bindDeleteRow() {

                $("button.deleteRow").off('click').on('click', function () {

                    let objTr = $(this).closest('tr');
                    var id = $(this).attr('value');
                    layer.confirm('真的删除行么', function (index) {

                        if (id === undefined) {
                            objTr.remove();
                            layer.close(index);
                        } else {

                            let url = "{{ url('admin/orgGoodsSpecs/destroy') }}/" + id;
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
                                    objTr.remove();
                                    layer.close(index);
                                }
                            });
                        }

                    });

                })
            }

            // 引入product商品
            function bindProduct() {

                $("button.addProduct").off('click').on('click', function () {
                    let tr = $(this).parents('tr');
                    let trElements = [];

                    let content = '<div style="padding-left: 50px;padding-top: 30px"><div class="layui-form" id="queryForm">' +
                        '<div class="layui-form-item">' +
                        '<div class="layui-inline">' +
                        ' <div class="layui-input-inline">' +
                        '<input type="text" class="layui-input" name="item_name" placeholder="商品名称"> ' +
                        '</div>' +
                        ' <div class="layui-input-inline">' +
                        '<input type="text" class="layui-input" name="spec_code" placeholder="规格编码"> ' +
                        '</div>' +
                        ' <div class="layui-input-inline">' +
                        '<input type="text" class="layui-input" name="item_code" placeholder="商品编码"> ' +
                        '</div>' +
                        ' <div class="layui-input-inline">' +
                        '<input type="text" class="layui-input" name="bar_code" placeholder="商品条码"> ' +
                        '</div>' +
                        '<div class="layui-inline"> ' +
                        '<div class="layui-input-inline">' +
                        '<button class="layui-btn" id="searchArtist" lay-submit lay-filter="formSearchArtist">查询</button>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div><table id="chooseERPTable" lay-filter="tblERPTable"></table></div></div>';


                    layer.open({
                        type: 1,
                        area: ['80%', '80%'],
                        content: content,
                        success: function (layero, index) {
                            let table = layui.table;
                            table.render({
                                elem: '#chooseERPTable'
                                , url: "{{route('admin.products.list')}}"  //数据接口
                                , title: "ERP商品"
                                , toolbar: '#toolbarDemo'
                                , page: true //开启分页
                                , cols: [[ //表头
                                    {type: 'checkbox', fixed: 'left', event: 'aaa'}
                                    , {field: 'id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                                    , {field: 'spec_code', title: '规格编码', width: 120}
                                    , {field: 'item_code', title: '商品编码', width: 120}
                                    , {field: 'item_name', title: '商品名称', width: 220}
                                    , {field: 'bar_code', title: '商品条码', width: 120}
                                    , {field: 'article_number', title: '货号', width: 120}
                                    , {field: 'color', title: '颜色', width: 120}
                                    , {field: 'other_prop', title: '其他规格', width: 120}
                                    , {field: 'price', title: '标价', width: 120}
                                    , {
                                        field: 'quantity0', title: '实际库存', width: 100, templet: function (row) {
                                            if (row.stock == null) {
                                                return 0;
                                            } else {
                                                return row.stock.quantity;
                                            }
                                        }
                                    }
                                    , {
                                        field: 'available0', title: '可用库存', width: 100, templet: function (row) {
                                            if (row.stock == null) {
                                                return 0;
                                            } else {
                                                return row.stock.available;
                                            }
                                        }
                                    }
                                ]]
                                , parseData: function (res) {

                                    return {
                                        "code": res.success ? 0 : 1, //解析接口状态
                                        "msg": res.message, //解析提示文本
                                        "count": res.model.total, //解析数据长度
                                        "data": res.model.data //解析数据列表
                                    };
                                }
                            })

                            //监听查询按钮
                            form.on('submit(formSearchArtist)', function (data) {
                                let queryFields = data.field;
                                if (queryFields.layTableCheckbox){
                                    delete queryFields.layTableCheckbox;
                                }

                                //表格数据重载
                                table.reload('chooseERPTable', {
                                    where: {
                                        queryFields
                                    },
                                    page: {
                                        curr: 1
                                    }
                                });

                            });

                            table.on('toolbar(tblERPTable)', function (obj) {
                                var checkStatus = table.checkStatus(obj.config.id);
                                var layEvent = obj.event; //获得 lay-event 对应的值

                                if (layEvent === 'getCheckData') {
                                    var data = checkStatus.data;
                                    trElements = data;

                                }

                                for (let tr_i = 0; tr_i <= trElements.length; tr_i++) {
                                    if (trElements[tr_i] === undefined)
                                        continue;
                                    let data = trElements[tr_i];
                                    let trHtml = '<tr id="' + i + '" xmlns="http://www.w3.org/1999/html">' +
                                        '<td style="width: 56px">' +
                                        '<div class="layui-btn-group">' +
                                        '<button class="layui-btn layui-btn-primary deleteRow"><i class="layui-icon layui-icon-delete"></i></button>' +
                                        ' </div>' +
                                        '</td>' +
                                        ' <td><button class="layui-btn layui-btn-primary choosePic" param="spec_img" num="' + i + '"> ' +
                                        '     <i class="layui-icon layui-icon-upload-drag"></i>选择图片\n' +
                                        '</button><div class="uploader-list am-cf" id="uploader-list"></div></td>' +
                                        '<td><input type="text" name="specs[' + i + '][org_goods_no]"  required lay-verify="required"  class="layui-input" value="' + data.item_code + '" ></td>' +
                                        '<td><input type="text" name="specs[' + i + '][org_goods_price]"  required lay-verify="required"  class="layui-input" value="' + data.price + '"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][line_price]"  required lay-verify="required"  class="layui-input" value="' + data.price + '"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][retail_price]"  required lay-verify="required"  class="layui-input" value="' + data.price + '"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][color]" required lay-verify="required"  class="layui-input"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][size]" required lay-verify="required"  class="layui-input"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][virtual_quantity]" required lay-verify="required"  class="layui-input"></td>' +

                                        '<td><input type="text" name="specs[' + i + '][virtual_sold_num]"  required lay-verify="required"  class="layui-input" ></td>' +
                                        '<td><input type="text" name="specs[' + i + '][barcode]" class="layui-input" value="' + data.bar_code + '"></td>' +
                                        // '<td><input type="text" name="specs[' + i + '][weight]"    class="layui-input" value=""></td>' +
                                        '<td><input type="text" name="specs[' + i + '][spec_name]" class="layui-input" value="' + data.other_prop + '"></td>' +
                                        '<td><button class="layui-btn layui-btn-sm" onclick="publish_status(this)" >上架</button>' +
                                        ' <input type="hidden" name="specs[' + i + '][publish_status]"  class="layui-input" id="publish_status0" hidden value="{{\App\Models\OrgGood::PUBLISH_STATUS_UPPER}}"></td>' +
                                        '<td><input type="text" name="specs[' + i + '][spec_code]"  required lay-verify="required"  class="layui-input" value="' + data.spec_code + '"></td>';


                                    if (data.stock !== null) {
                                        trHtml += '<input type="hidden" name="specs[' + i + '][actual_inventory]" class="layui-input" value="' + data.stock.quantity + '">';
                                    } else {
                                        trHtml += '<input type="hidden" name="specs[' + i + '][actual_inventory]" class="layui-input" value="0">';
                                    }
                                    trHtml += '</tr>';
                                    $("#tbl_bj tbody").append(trHtml);
                                    i++;
                                    bindDeleteRow();
                                    layer.close(index); //如果设定了yes回调，需进行手工关闭

                                }

                            })


                        }
                    })


                })
            }

            // 添加参数配置
            $('#addParam').on('click', function (data) {
                let _token = "{{csrf_token()}}";
                let url = "{{url('admin/specs/list')}}";
                var selectElement = '<option value="">请选择</option>';

                $.ajax({
                    type: 'GET', url: url, data: {"_token": _token}, dataType: "JSON",
                    success: function (result) {
                        specsArray = result.model.data;

                        $.each(specsArray, function (index, value) {
                            selectElement += '<option value="' + value.id + '">' + value.spec_name + '</option>';
                        });
                        let trHtml = '<tr><td><select lay-filter="param"   required lay-verify="required"  >' + selectElement + '</select></td>\n' +
                            '                 <td class="specsCheckbox"></td>\n' +
                            '                 <td> <button class="layui-btn layui-btn-primary layui-btn-sm delParam">' +
                            '<i class="layui-icon layui-icon-delete"></i>' +
                            '</button></td></tr>';

                        $("#tbl_param tbody").append(trHtml);
                        layui.form.render(); // 重新渲染
                    },
                    error: function () {
                    }
                });
            })


        });

        // 选择图片
        $(document).on('click', '.choosePic', function () {
            let btn = $(this);
            let url = "{{ route('admin.uploadFiles.picWindow') }}";
            let param = $(btn).attr('param');
            let num = $(btn).attr('num');

            layer.open({
                type: 2,
                title: '选择图片',
                area: ['1200px', '680px'],
                fixed: false, //不固定
                maxmin: true,
                content: url,
                btn: '确定',
                yes: function (index, layero) {
                    var body = top.layer.getChildFrame('body', index);
                    var imgElement = '';
                    let ids = [];
                    let imgs = [];
                    $(body).find(".active").each(function () {
                        ids.push($(this).attr('data-file-id'));
                    });
                    for (var i = 0; i < ids.length; i++) {
                        imgs[i] = [];
                        imgs[i]['url'] = $(body).find("#ng-scope_" + ids[i]).attr('data-file-path');
                        imgs[i]['path'] = $(body).find("#ng-scope_" + ids[i]).attr('path');
                        imgs[i]['id'] = ids[i];


                        if (param === "{{\App\Models\OrgGoodImage::PROPERTY_LOGO}}") {
                            imgElement = '<div class="file-item">\n' +
                                '                                    <a href="' + imgs[i]['url'] + '" title="点击查看大图" target="_blank">\n' +
                                '                                        <img src="' + imgs[i]['url'] + '">\n' +
                                '                                    </a>\n' +
                                '                                    <input type="hidden" name="images[' + param + '][]" value="' + imgs[i]['id'] + '">\n' +
                                '                                    <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>\n' +
                                '                                </div>';
                            $(btn).next().html(imgElement);
                        } else if (param === "spec_img") {
                            imgElement = '<div class="file-item">\n' +
                                '                                    <a href="' + imgs[i]['url'] + '" title="点击查看大图" target="_blank">\n' +
                                '                                        <img src="' + imgs[i]['url'] + '">\n' +
                                '                                    </a>\n' +
                                '                                    <input type="hidden" name="specs[' + num + '][image_url]" value="' + imgs[i]['path'] + '">\n' +
                                '                                    <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>\n' +
                                '                                </div>';
                            $(btn).css('display', 'none'); // 规格图片选择
                            $(btn).next().html(imgElement);
                        } else {
                            imgElement += '<div class="file-item">\n' +
                                '                                    <a href="' + imgs[i]['url'] + '" title="点击查看大图" target="_blank">\n' +
                                '                                        <img src="' + imgs[i]['url'] + '">\n' +
                                '                                    </a>\n' +
                                '                                    <input type="hidden" name="images[' + param + '][]" value="' + imgs[i]['id'] + '">\n' +
                                '                                    <i class="layui-icon layui-icon-close" onclick="delImg(this)"></i>\n' +
                                '                                </div>';


                        }

                    }
                    if (param !== "{{\App\Models\OrgGoodImage::PROPERTY_LOGO}}" && param !== "spec_img")
                        $(btn).next().append(imgElement);

                    layer.close(index); //如果设定了yes回调，需进行手工关闭
                }

            });
        })
        // 添加商品分类
        $("#add_category").on("click", function () {
            let url = "{{ route('admin.categories.create.window') }}";
            layer.open({
                type: 2,
                title: '添加品类',
                area: ['800px', '650px'],
                fixed: false, //不固定
                maxmin: true,
                content: url
            });
        })

        // 添加标签
        $(document).on('click', '.am-btn-add', function () {
            let prevDiv = $(this).parent();
            let flag = $(this).prev().attr('flag');
            let lableI = ++flag;
            let addElement = '  <input type="text" class="ipt-specItem am-fl am-field-valid" name="label_values[' + lableI + ']" flag="' + lableI + '" style="margin-left: 10px"><button type="button" class="am-btn am-fl am-btn-add ">添加</button>';


            if (!$(this).prev().val()) {
                layer.msg('标签内容不能为空');
                return;
            }

            $(this).html('删除');
            $(this).removeClass('am-btn-add');
            $(this).addClass('am-btn-del');
            $(prevDiv).append(addElement);
        });

        // 删除标签
        $(document).on('click', '.am-btn-del', function () {
            $(this).prev().remove();
            $(this).remove();
        });

        // 移除商品图片
        function delImg(obj) {
            $(obj).parents('.uploader-list').prev().css('display', 'block');
            $(obj).parent('.file-item').remove();
        }

        // 上架下架样式切换
        function publish_status(obj) {
            let flag = $(obj).next().val();
            if (flag == "{{\App\Models\OrgGood::PUBLISH_STATUS_LOWER}}") {
                $(obj).next().val("{{\App\Models\OrgGood::PUBLISH_STATUS_UPPER}}");
                $(obj).html('上架');
            } else {
                $(obj).next().val("{{\App\Models\OrgGood::PUBLISH_STATUS_LOWER}}");
                $(obj).html('下架');
            }
        }

        // 升级版本号
        function upVersion() {

            layer.confirm('确定升级该产品版本吗？升级之后就女年份就打架大家看法啦', function (index) {
                let id = "{{$orgGood->id}}";
                let url = "{{ url('admin/orgGoods/upVersion') }}/" + Number(id);
                let _token = "{{csrf_token()}}";
                let version = Number($('#version').val()) + 1;


                $.post(url, {"_token": _token, 'version': version}, function (result) {
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
                        window.location.reload();

                    }
                });


            });
        }

        // 刷新模板
        function refreshFreight() {
            let _token = "{{csrf_token()}}";
            let url = "{{url('admin/deliveries/list')}}";
            var selectElement = '<option value="">请选择运费模板</option>';
            $.ajax({
                type: 'GET', url: url, data: {"_token": _token}, dataType: "JSON",
                success: function (result) {
                    specsArray = result.model.data;

                    $.each(specsArray, function (index, value) {
                        selectElement += '<option value="' + value.id + '">' + value.name + '</option>';
                    });

                    $("#delivery").html(selectElement);
                    layui.form.render(); // 重新渲染
                },
                error: function () {
                }
            });
        }
    </script>
@endpush