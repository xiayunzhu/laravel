<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/18
 * Time: 16:21
 */

namespace App\Handlers;


use App\Exceptions\GoodsException;
use App\Models\Category;
use App\Models\Goods;
use App\Models\GoodsSpecname;
use App\Models\OrgGood;
use App\Models\OrgGoodsLabels;
use App\Models\OrgGoodsSpec;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class OrgGoodHandler
{

    private $orgGoodsImageHandler;
    private $orgGoodsHasSpecHandler;
    private $orgGoodsSpecHandler;
    private $tmpSize = [];
    private $size = [];

    public function __construct(OrgGoodsImageHandler $orgGoodsImageHandler, OrgGoodsHasSpecHandler $orgGoodsHasSpecHandler, OrgGoodsSpecHandler $orgGoodsSpecHandler)
    {
        $this->orgGoodsImageHandler = $orgGoodsImageHandler;
        $this->orgGoodsHasSpecHandler = $orgGoodsHasSpecHandler;
        $this->orgGoodsSpecHandler = $orgGoodsSpecHandler;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = OrgGood::query();

        $sorting = $request->get('sorting');
        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['name'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'name') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
                ## 分类查询
                if (strpos($field, 'category_id') !== false) {
                    $categoryIds = CategoryHandler::searchCategories($value);
                    $query->whereIn($field, $categoryIds);
                }
            }
        }

        $query->where('publish_status', OrgGood::PUBLISH_STATUS_UPPER);

        $idsIn = $request->get('idsIn');
        if (is_array($idsIn)) {
            ## 优惠券商品在范围内查询
            $data = $query->whereIn('id', $idsIn)
                ->select(['id', 'name','sales_actual'])
                ->withCount(['specs as quantity' => function ($q) {
                    $q->select(\DB::raw('sum(quantity) as quantity'));
                }])->withCount(['specs as org_goods_price_max' => function ($q) {
                    $q->select(\DB::raw('max(org_goods_price) as org_goods_price_max'));
                }])->withCount(['specs as org_goods_price_min' => function ($q) {
                    $q->select(\DB::raw('min(org_goods_price) as org_goods_price_min'));
                }])->with(['logo_image'])->get();
        } else {

            $data = $query->with(['logo_image', 'lables'])->select(['id', 'name'])
                ->withCount(['specs as org_goods_price_max' => function ($q) {
                    $q->select(\DB::raw('max(org_goods_price) as org_goods_price_max'));
                }])->withCount(['specs as org_goods_price_min' => function ($q) {
                    $q->select(\DB::raw('min(org_goods_price) as org_goods_price_min'));
                }])->withCount(['specs as commission_max' => function ($q) {
                    $q->select(\DB::raw('max(org_goods_price*commission_rate*0.01) as commission_max'));
                }])->withCount(['specs as commission_min' => function ($q) {
                    $q->select(\DB::raw('min(org_goods_price*commission_rate*0.01) as commission_min'));
                }])->get();
        }

        $data = $data->toArray();

        if ($sorting) {
            switch ($sorting) {
                case 'commissionDesc':
                    ## 佣金降序
                    $data = arraySort($data, 'commission_min', 'desc');
                    break;
                case 'commissionAsc':
                    ## 佣金升序
                    $data = arraySort($data, 'commission_min');
                    break;
                case 'priceDesc':
                    ## 价格降序
                    $data = arraySort($data, 'org_goods_price_min', 'desc');
                    break;
                case 'priceAsc':
                    ## 价格升序
                    $data = arraySort($data, 'org_goods_price_min');
                    break;
                case 'newProduct':
                    ## 新品
                    break;
                case 'all':
                    ## 综合
                    break;
            }
        }
        if ($data) {
            $data = fmt_array($data, ['file_url' => 'image_link']);
        }

        ## 分页处理
        $perPage = $request->get('per_page') ? $request->get('per_page') : 10;
        if ($request->get('page')) {
            $currentPage = $request->get('page');
            $currentPage = $currentPage <= 0 ? 1 : $currentPage;
        } else {
            $currentPage = 1;
        }

        $item = array_slice($data, ($currentPage - 1) * $perPage, $perPage); //注释1
        $total = count($data);

        $paginator = new LengthAwarePaginator($item, $total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);


        return $paginator;
    }

    /**
     * 单个查询
     * @param $id
     * @return mixed
     */
    public function first($id)
    {
        return OrgGood::find($id);
    }

    /**
     * 商品详情
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function detail(Request $request)
    {
        $orgGoodId = $request->get('id');

        $orgGood = OrgGood::where('id', $orgGoodId)->with(['main_images', 'params', 'specs:id,org_goods_id,org_goods_price,spec_name,quantity,image_url', 'specs.goodSpecs'])->select(['id', 'name'])
            ->withCount(['specs as org_goods_price_max' => function ($q) {
                $q->select(\DB::raw('max(org_goods_price) as org_goods_price_max'));
            }])->withCount(['specs as org_goods_price_min' => function ($q) {
                $q->select(\DB::raw('min(org_goods_price) as org_goods_price_min'));
            }])->withCount(['specs as commission_max' => function ($q) {
                $q->select(\DB::raw('max(org_goods_price*commission_rate*0.01) as commission_max'));
            }])->withCount(['specs as commission_min' => function ($q) {
                $q->select(\DB::raw('min(org_goods_price*commission_rate*0.01) as commission_min'));
            }])->withCount(['specs as quantity' => function ($q) {
                $q->select(\DB::raw('sum(virtual_quantity) as quantity'));
            }])->withCount(['specs as sold_num' => function ($q) {
                $q->select(\DB::raw('sum(virtual_sold_num) as sold_num'));
            }])->first();


        if (!$orgGood)
            throw new \Exception('商品不存在');

        $orgGood = $orgGood->toArray();
        ## 商品规格组装
        $org_goods_specs_ids = OrgGoodsSpec::where('org_goods_id', $orgGoodId)->pluck('id');
        if ($org_goods_specs_ids) {
            $org_goods_specs_ids = $org_goods_specs_ids->toArray();
            $specs_names = GoodsSpecname::whereIn('org_goods_specs_id', $org_goods_specs_ids)->distinct()->pluck('spec_name');
            foreach ($specs_names as $key => $value) {
                $orgGood['specs_params'][$key]['specs_name'] = $value;
                $orgGood['specs_params'][$key]['specs_value'] = GoodsSpecname::whereIn('org_goods_specs_id', $org_goods_specs_ids)->where('spec_name', $value)->distinct()->pluck('spec_value');
            }
        }

        return $orgGood;
    }


    /**
     * 参数选择
     *
     * @param Request $request
     * @return mixed
     */
    public function spec(Request $request)
    {

        $where = $request->only(['color', 'org_goods_id', 'size']);
        $orgGoodsSpec = OrgGoodsSpec::where($where)->first(['id', 'org_goods_price', 'color', 'size', 'virtual_quantity', 'image_url']);

        return $orgGoodsSpec;
    }

    /**
     * 商品表 复制
     *
     * @param Request $request
     * @return mixed
     */
    public function copyFormOrg(Request $request)
    {

        $org_goods_id = $request->get('org_goods_id');
        $name = $request->get('name');
        $model = new OrgGood();

        \DB::transaction(function () use (&$model, $org_goods_id, $name) {

            $org_goods = $this->first($org_goods_id);
            if (!$org_goods) {
                throw new GoodsException('模板商品未查询信息');
            }

            ## 商品主信息
            $data = $org_goods->toArray();
            $data['title'] = $data['name'] = $name;
            unset($data['id']);
            $model = $this->store($data);
            $new_org_goods_id = $model->id;
            ## 商品图片
            $org_goods_images = $org_goods->images;
            foreach ($org_goods_images as $org_goods_image) {
                $org_goods_image_arr = $org_goods_image->toArray();
                $org_goods_image_arr['org_goods_id'] = $new_org_goods_id;
                $this->orgGoodsImageHandler->store($org_goods_image_arr);
            }


            ## 商品参数规格
            $org_goods_has_specs = $org_goods->has_specs;
            foreach ($org_goods_has_specs as $org_goods_has_spec) {
                $org_goods_has_spec_arr = $org_goods_has_spec->toArray();
                $org_goods_has_spec_arr['org_goods_id'] = $new_org_goods_id;
                $this->orgGoodsHasSpecHandler->store($org_goods_has_spec_arr);
            }

            $org_goods_specs = $org_goods->specs;
            ## 商品SKU
            foreach ($org_goods_specs as $org_goods_spec) {
                $row = $org_goods_spec->toArray();
                unset($row['id']);
                $row['org_goods_id'] = $new_org_goods_id;
                $this->orgGoodsSpecHandler->store($row);
            }

            ## 商品标签Lables
            $org_goods_lables = $org_goods->lables;
            foreach ($org_goods_lables as $org_goods_lable) {
                $row = $org_goods_lable->toArray();
                unset($row['id']);
                $row['org_goods_id'] = $new_org_goods_id;
                OrgGoodsLabels::create($row);
            }

        }, 1);

    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $row = [];
        foreach (OrgGood::$fields as $field) {
            if (isset($data[$field])) {
                $row[$field] = $data[$field];
            }
        }
        return OrgGood::create($row);
    }
}