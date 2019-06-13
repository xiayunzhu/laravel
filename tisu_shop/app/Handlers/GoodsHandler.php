<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/19
 * Time: 16:00
 */

namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsGroupItem;
use App\Models\GoodsImage;
use App\Models\GoodsSpec;
use App\Models\OrgGood;
use App\Models\OrgGoodsSpec;
use Illuminate\Http\Request;

class GoodsHandler
{

    /**
     * 字段
     *
     * @var array
     */
    private $fields = ['brand_id', 'category_id', 'content', 'deduct_stock_type', 'delivery_id', 'goods_sort', 'introduction', 'name', 'publish_status', 'sales_actual', 'sales_initial', 'sales_status', 'shop_id', 'spec_type', 'title', 'version', 'org_goods_specs_id'];

    /**
     * @var OrgGoodHandler
     */
    private $orgGoodHandler;

    private $goodsImageHandler;
    private $goodsSpecHandler;
    private $tmpSize = [];
    private $size = [];
    const SKU_NEW = 'NEW';
    const SKU_DONE = 'DONE';

    public static $skuMap = [
        self::SKU_NEW => "新增",
        self::SKU_DONE => "已添加过",
    ];

    public function __construct(OrgGoodHandler $orgGoodHandler, GoodsImageHandler $goodsImageHandler, GoodsSpecHandler $goodsSpecHandler, GoodsHasSpecHandler $goodsHasSpecHandler)
    {
        $this->orgGoodHandler = $orgGoodHandler;
        $this->goodsImageHandler = $goodsImageHandler;
        $this->goodsSpecHandler = $goodsSpecHandler;
        $this->goodsHasSpecHandler = $goodsHasSpecHandler;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = Goods::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'sales_status', 'publish_status', 'category_id', 'name'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');

        $idsIn = $request->get('idsIn');
        if (is_array($idsIn)) {
            ## 优惠券商品在范围内查询
            $data = $query->whereIn('org_goods_id', $idsIn)
                ->select(['id', 'name', 'quantity', 'sales_actual'])
                ->withCount(['specs as goods_price_max' => function ($q) {
                    $q->select(\DB::raw('max(goods_price) as goods_price_max'));
                }])->withCount(['specs as goods_price_min' => function ($q) {
                    $q->select(\DB::raw('min(goods_price) as goods_price_min'));
                }])
                ->with(['logo_image'])->paginate($per_page);
        } else {
            $data = $query->paginate($per_page, ['id', 'publish_status', 'name', 'goods_price', 'sales_actual', 'quantity']);
        }
        return $data;
    }


    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];
        foreach (Goods::$fields as $field) {
            if (isset($data[$field])) {
                $row[$field] = $data[$field];
            }
        }

        return Goods::create($row);
    }

    /**
     * 检查原商品是否已选
     *
     * @param $org_goods_id
     * @param $shop_id
     * @return boolean
     */
    public function checkGoods($org_goods_id, $shop_id)
    {
        $count = Goods::where(
            [
                ['org_goods_id', '=', $org_goods_id],
                ['shop_id', '=', $shop_id]
            ]
        )->count();

        if ($count > 0) {
            return false;## 已选原商品
        }
        return true;## 未选原商品
    }

    /**
     * 检查SKU是否已选
     *
     * @param $org_goods_specs_id
     * @param $shop_id
     * @return array
     * @throws GoodsException
     */
    public function checkGoodsSpec($org_goods_specs_id, $shop_id)
    {
        if (!OrgGoodsSpec::find($org_goods_specs_id))
            throw new GoodsException('选款信息不存在');

        $good_spec = GoodsSpec::where(
            [
                ['org_goods_specs_id', '=', $org_goods_specs_id],
                ['shop_id', '=', $shop_id]
            ]
        )->first();

        if ($good_spec) {
            return ['status' => true, 'msg' => "平台仓库SKU商品ID：" . $org_goods_specs_id . "  " . $good_spec->spec_name . "已添加过到我的仓库"];
        }
        return ['status' => false, 'msg' => ''];
    }


    /**
     * 商品表复制 针对SKU商品复制
     *
     * @param $shop_id
     * @param $org_goods_specs_id
     * @param string $publish_status
     * @return string
     * @throws GoodsException
     */
    public function copyFormOrg($shop_id, $org_goods_specs_id, $publish_status = Goods::PUBLISH_STATUS_LOWER)
    {

        ## 校验SKU商品是否存在
        $checkGoodsSpecRes = $this->checkGoodsSpec($org_goods_specs_id, $shop_id);
        if ($checkGoodsSpecRes['status']) {
            return self::SKU_DONE;
        }
        $org_goods_id = OrgGoodsSpec::find($org_goods_specs_id)->org_goods_id;
        ## 校验是否已存在
        $checkGoods = $this->checkGoods($org_goods_id, $shop_id);

        $model = new Goods();
        \DB::transaction(function () use (&$model, $org_goods_id, $shop_id, $publish_status, $org_goods_specs_id, $checkGoods) {
            if ($checkGoods) {

                $org_goods = $this->orgGoodHandler->first($org_goods_id);
                if (!$org_goods) {
                    throw new GoodsException('模板商品未查询信息');
                }

                ## 商品主信息
                $data = $org_goods->toArray();
                $data['shop_id'] = $shop_id;
                $data['org_goods_id'] = $org_goods_id;
                $data['publish_status'] = $publish_status;
                $model = $this->store($data);
                $goods_id = $model->id;
                ## 商品图片
                $org_goods_images = $org_goods->images;
                foreach ($org_goods_images as $org_goods_image) {
                    $org_goods_image_arr = $org_goods_image->toArray();
                    $org_goods_image_arr['goods_id'] = $goods_id;
                    $org_goods_image_arr['shop_id'] = $shop_id;

                    $this->goodsImageHandler->store($org_goods_image_arr);
                }

            }

            ## 商品SKU
            $org_goods_spec = OrgGoodsSpec::find($org_goods_specs_id);
            $goods = Goods::where(['org_goods_id' => $org_goods_id, 'shop_id' => $shop_id])->first();
            $row = [
                'goods_id' => $goods->id,
                'shop_id' => $shop_id,
                'goods_no' => $org_goods_spec->org_goods_no,
                'goods_price' => $org_goods_spec->org_goods_price,
                'line_price' => $org_goods_spec->line_price,
                'fx_price' => $org_goods_spec->line_price,
                'retail_price' => $org_goods_spec->retail_price,
                'quantity' => $org_goods_spec->virtual_quantity,
                'virtual_quantity' => $org_goods_spec->virtual_quantity,
                'quantity_offset' => $org_goods_spec->virtual_quantity - $org_goods_spec->virtual_quantity,
                'barcode' => $org_goods_spec->barcode,
                'weight' => $org_goods_spec->weight,
                'spec_code' => $org_goods_spec->spec_code,
                'color' => $org_goods_spec->color,
                'size' => $org_goods_spec->size,
                'virtual_sold_num' => $org_goods_spec->virtual_sold_num,
                'publish_status' => $publish_status,
                'spec_name' => $org_goods_spec->spec_name,
                'org_goods_specs_id' => $org_goods_spec->id,
                'image_url' => $org_goods_spec->image_url,
            ];
            $this->goodsSpecHandler->store($row);

        }, 1);

        return self::SKU_NEW;

    }


    /**
     * 添加到仓库【营销活动】
     *
     * @param $shop_id
     * @param array $org_goods_ids
     * @param string $publish_status
     * @return array
     */
    public function goodAdd($shop_id, $org_goods_ids = [], $publish_status = Goods::PUBLISH_STATUS_UPPER)
    {
        try {
            $org_goods = OrgGood::whereIn('id', $org_goods_ids)->get();
            if (!$shop_id || !is_array($org_goods_ids)) {
                throw new \Exception('参数错误');
            }
            \DB::transaction(function () use ($org_goods, $shop_id, $org_goods_ids, $publish_status) {

                foreach ($org_goods as $org_good) {
                    $goodSpecs = $org_good->specs;
                    if (!count($goodSpecs->toArray())) {
                        throw new \Exception('商品SKU信息不存在');
                    }
                    foreach ($goodSpecs as $goodSpec) {
                        ## 添加到卖家仓库
                        $this->copyFormOrg($shop_id, $goodSpec->id, $publish_status);
                    }
                }

                ## 上架操作
                $goods_ids = Goods::whereIn('org_goods_id', $org_goods_ids)->where('shop_id', $shop_id)->pluck('id')->toArray();
                if (count($goods_ids)) {
                    $goods_res = Goods::whereIn('org_goods_id', $org_goods_ids)->where('shop_id', $shop_id)->update(['publish_status' => Goods::PUBLISH_STATUS_UPPER]);
                    $goods_spec_res = GoodsSpec::whereIn('goods_id', $goods_ids)->update(['publish_status' => GoodsSpec::PUBLISH_STATUS_UPPER]);

                    if (!$goods_spec_res || !$goods_res) {

                        throw new \Exception('上架失败');
                    }
                }
            });


        } catch (\Exception $exception) {
            return ['status' => false, 'msg' => $exception->getMessage()];
        }
        return ['status' => true, 'msg' => '成功'];

    }

    /**
     * 商品上架,商品下架
     * @param Request $request
     * @return bool
     * @throws GoodsException
     */
    public function upper_lower(Request $request)
    {
        #@todo 校验权限-是否是自己的商品
        $goods_id = $request->get('goods_id');
        $handle = $request->get('handle');

        $goods = Goods::find($goods_id);
        if ($goods) {


            if ($handle == 'upper')
                $goods->publish_status = Goods::PUBLISH_STATUS_UPPER;
            elseif ($handle == 'lower')
                $goods->publish_status = Goods::PUBLISH_STATUS_LOWER;

            $res = $goods->save();

            if ($res)
                return true;
            else
                throw new GoodsException('操作失败');
        } else {
            throw new GoodsException('商品信息未查询到', 10002);
        }
    }


    /**
     * 详情页信息
     *
     * @param Request $request
     * @return mixed
     * @throws GoodsException
     */
    public function detail(Request $request)
    {
        $goods_id = $request->get('goods_id');

        $goods = Goods::find($goods_id);
        if (!$goods)
            throw new GoodsException('商品信息未查询到', 10002);

        return $goods;

    }

    /**
     * APP 商品详情
     *
     * @param Request $request
     * @return mixed
     * @throws GoodsException
     */
    public function appDetail(Request $request)
    {
        $goods_id = $request->get('goods_id');

        $goods = Goods::where('id', $goods_id)->first(['id', 'name', 'goods_price', 'category_id', 'brand_id', 'created_at', 'introduction']);
        if (!$goods)
            throw new GoodsException('商品信息未查询到', 10002);


        $goods->load(['specs:id,goods_id,publish_status,sold_num,quantity,org_goods_specs_id', 'specs.goodSpecs', 'logo_image', 'main_images', 'detail_images']);

        $goods = $goods->toArray();
        $group = GoodsGroupItem::where(['goods_id' => $goods_id])->first();
        $goods['goods_group_id'] = $group ? $group->goods_group_id : 0;
        $goods['created_at'] = strtotime($goods['created_at']);
        $goods['category_name'] = $goods['category_id'] ? CategoryHandler::parentCategory($goods['category_id'])->name : '';
        $goods['brand_name'] = $goods['brand_id'] ? Brand::find($goods['brand_id'])->name : '';
        return $goods;
    }


    /**
     * 修改商品主信息
     *
     * @param Request $request
     * @return mixed
     * @throws GoodsException
     */
    public function update(Request $request)
    {
        $goods_id = $request->get('goods_id');
        $data = $request->only(['brand_id', 'category_id', 'images', 'name', 'introduction', 'goods_group_id']);
        $goods = Goods::find($goods_id);
        if (!$goods)
            throw new GoodsException("该商品不存在");

        \DB::transaction(function () use (&$goods, $data, $goods_id) {

            ## 修改商品主图片&&详情图片
            if (isset($data['images'])) {
                foreach ($data['images']['main'] as $image_data) {

                    $is_exist = GoodsImage::where(['image_id' => $image_data['image_id'], 'goods_id' => $goods_id, 'property' => 'main'])->first();
                    if ($is_exist) {
                        ## 图片已经存在   修改相关信息
                        $is_exist->file_url = $image_data['file_url'];
                        $is_exist->sort = $image_data['sort'];
                        $res = $is_exist->save();
                        if (!$res) {
                            throw new GoodsException("主图片修改失败");
                        }
                        continue;
                    }
                    $image_data['goods_id'] = $goods_id;
                    $image_data['shop_id'] = $goods->shop_id;
                    $image_data['create_time'] = time();
                    $image_data['property'] = GoodsImage::PROPERTY_MAIN;
                    $res = $this->goodsImageHandler->store($image_data);
                    if (!$res) {
                        throw new GoodsException("主图片添加失败");
                    }
                }
                foreach ($data['images']['detail'] as $image_data) {
                    $is_exist = GoodsImage::where(['image_id' => $image_data['image_id'], 'goods_id' => $goods_id, 'property' => 'detail'])->first();

                    if ($is_exist) {
                        ## 图片已经存在   修改相关信息
                        $is_exist->file_url = $image_data['file_url'];
                        $is_exist->sort = $image_data['sort'];
                        $res = $is_exist->save();
                        if (!$res) {
                            throw new GoodsException("详情图片修改失败");
                        }
                        continue;
                    }
                    $image_data['goods_id'] = $goods_id;
                    $image_data['shop_id'] = $goods->shop_id;
                    $image_data['create_time'] = time();
                    $image_data['property'] = GoodsImage::PROPERTY_DETAIL;
                    $res = $this->goodsImageHandler->store($image_data);
                    if (!$res) {
                        throw new GoodsException("详情图片添加失败");
                    }
                }
                unset($data['images']);
            }
            ## 分组修改
            if (isset($data['goods_group_id'])) {
                $goodsGroupItem = GoodsGroupItem::where("goods_id", $goods_id)->first();
                if (count($goodsGroupItem)) {
                    $goodsGroupItem->goods_group_id = $data['goods_group_id'];
                    $res = $goodsGroupItem->save();
                } else {
                    $res = GoodsGroupItem::create(['goods_group_id' => $data['goods_group_id'], 'goods_id' => $goods_id, 'shop_id' => $goods->shop_id]);
                }
                if (!$res) {
                    throw new GoodsException("分组修改失败");
                }
                unset($data['goods_group_id']);
            }

            foreach ($data as $field => $value) {
                $goods->$field = $value;
            }
            $goods->save();

        });

        return true;

    }

    /**
     * 参数选择
     *
     * @param Request $request
     * @return mixed
     */
    public function spec(Request $request)
    {

        $where = $request->only(['color', 'goods_id', 'size']);
        $goodsSpec = GoodsSpec::where($where)->first(['id', 'goods_price', 'color', 'size', 'image_url']);

        $goodsSpec = $goodsSpec ? $goodsSpec : [];
        return $goodsSpec;
    }
}