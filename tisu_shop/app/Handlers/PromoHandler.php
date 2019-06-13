<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/21
 * Time: 15:58
 */

namespace App\Handlers;


use App\Exceptions\PromosException;
use App\Models\BuyerCoupon;
use App\Models\Goods;
use App\Models\Promo;
use App\Models\PromoItem;
use Illuminate\Http\Request;

class PromoHandler
{

    private $promoItemHandler;
    private $buyerCouponHandler;

    public function __construct(PromoItemHandler $promoItemHandler, BuyerCouponHandler $buyerCouponHandler)
    {
        $this->promoItemHandler = $promoItemHandler;
        $this->buyerCouponHandler = $buyerCouponHandler;

    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = Promo::query();

        //查询条件处理
        $query->where('status', '<>', Promo::PROMO_STATUS_UNABLE);
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['shop_id', 'type'])) {
                    if (!empty($value)) {
                        $query->where($field, $value);
                    }
                }
            }
        }
        ## 状态筛选
        $proceed_status = $request->get('proceed_status') ? $request->get('proceed_status') : Promo::PROCEED_STATUS_PREPARE;
        switch ($proceed_status) {
            ## 未开始
            case Promo::PROCEED_STATUS_PREPARE:
                $query->where([['take_end', '>=', time()], ['status', '=', Promo::PROMO_STATUS_ENABLE]]);
                break;
            ## 进行中
            case Promo::PROCEED_STATUS_ONGOING:
                $query->where([['take_end', '>=', time()], ['status', '=', Promo::PROMO_STATUS_ONGOING]])->whereRaw("total_count<>take_count");
                break;
            ## 已过期
            case Promo::PROCEED_STATUS_EXPIRED:
                $query->where([['take_end', '<', time()], ['total_count', '<>', 0]])->orWhereRaw("total_count=take_count");
                break;
            default:
                break;
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page, ['id', 'type', 'title', 'discount', 'require_threshold', 'credit_limit', 'range', 'take_count', 'used_count', 'effect_time', 'invalid_time']);
        if ($data) {
            $data = $data->toArray();
        }
        return $data;
    }

    /**
     * 优惠券添加
     *
     * @param Request $request
     * @return mixed
     * @throws PromosException
     */
    public function store(Request $request)
    {

        $row = $request->only(Promo::$fields);
        $good_ids = $request->get('good_ids') ? $request->get('good_ids') : 0;
        $row['take_begin'] = isset($row['take_begin']) ? strtotime($row['take_begin']) : 0;
        $row['take_end'] = isset($row['take_end']) ? strtotime($row['take_end']) : 0;
        $row['effect_time'] = isset($row['effect_time']) ? strtotime($row['effect_time']) : 0;
        $row['invalid_time'] = isset($row['invalid_time']) ? strtotime($row['invalid_time']) : 0;
        ## 用户类型为新用户 则可领取张数1张
        if ($row['apply_user'] == Promo::USER_TYPE_NEW)
            $row['tickets_available'] = 1;

        \DB::beginTransaction();
        $row['status'] = Promo::PROMO_STATUS_ENABLE;
        $promo = Promo::create($row);

        if (!is_array($good_ids) && $row['range'] != Promo::GOOD_RANGE_ALL_CAN) {
            \DB::rollback();
            throw new PromosException('good_ids 商品ID必须为Array类型');
        } elseif (is_array($good_ids)) {
            ## 指定商品
            $good_data['shop_id'] = $row['shop_id'];
            $good_data['promo_id'] = $promo->id;
            $good_data['status'] = PromoItem::STATUS_ENABLE;
            foreach ($good_ids as $good_id) {
                $good_data['goods_id'] = $good_id;
                $good = Goods::find($good_id);
                if (!$good) {
                    \DB::rollback();
                    throw new PromosException('商品不存在或已下架');
                }
                $tmp = $this->promoItemHandler->store($good_data);
                if (!$tmp) {
                    \DB::rollback();
                    throw new PromosException('指定商品失败');
                }
            }
        }

        \DB::commit();
        return $promo;
    }

    /**
     * 删除优惠券
     *
     * @param int $promo_id
     * @return mixed
     * @throws PromosException
     * @throws \App\Exceptions\BuyerCouponException
     */
    public function delete(int $promo_id)
    {
        \DB::beginTransaction();
        $promo = Promo::where('status', Promo::PROMO_STATUS_ENABLE)->find($promo_id);

        $is_buyer_coupon = BuyerCoupon::where('promo_id', $promo_id)->count();

        if ($is_buyer_coupon) {
            $res = $this->buyerCouponHandler->delete($promo_id);

            if (!$res) {
                \DB::rollback();
                throw new PromosException('用户优惠券删除失败');
            }
        }

        $promo->status = Promo::PROMO_STATUS_UNABLE;
        $resPromo = $promo->save();
        if (!$resPromo) {
            \DB::rollback();
            throw new PromosException('优惠券删除失败');
        }

        \DB::commit();
        return $promo;
    }
}