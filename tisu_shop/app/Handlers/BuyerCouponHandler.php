<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/23
 * Time: 16:47
 */

namespace App\Handlers;


use App\Exceptions\BuyerCouponException;
use App\Http\Requests\WeChat\BuyerCoupon\StoreRequest;
use App\Models\BuyerCoupon;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BuyerCouponHandler
{
    private $fields = ['shop_id', 'buyer_id', 'promo_id', 'effect_time', 'invalid_time', 'status'];

    public function page(Request $request)
    {
        $query = BuyerCoupon::query();
        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {

                if (in_array($field, ['buyer_id', 'shop_id'])) {
                    if (!empty($value)) {
                        $query->where($field, $value);
                    }
                }
            }
        }
        //每页数量
        $query->with(['promo'])->orderBy('status', 'asc')->orderByDesc('invalid_time');
        $data = $query->get()->toArray();
        $list = [];
        foreach ($data as $key => $row) {
            if ($row['invalid_time'] < time() && $row['status'] == BuyerCoupon::STATUS_EFFECT) {
                $row['status'] = BuyerCoupon::STATUS_INVALID;
            }
            $invalid_time[$key] = $row['invalid_time'];
            $status[$key] = $row['status'];
            $list[] = $row;
        }
        if (count($list) > 0) {
            array_multisort($status, SORT_ASC, $invalid_time, SORT_DESC, $list);
        }
        $page = $request->page ?: 1;
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);
        $offset = ($page * $per_page) - $per_page;
        $list = new LengthAwarePaginator(array_slice($list, $offset, $per_page, true), count($list), $per_page, $page, ['path' => $request->url(), 'query' => $request->query()]);
        return $list;
    }

    /**
     * @param Request $request
     * @param array $promo
     * @return mixed
     * @throws BuyerCouponException
     */
    public function store(Request $request, array $promo)
    {
        $promo_id = $request->get('promo_id');
        $buyer_id = $request->get('buyer_id');
        if (!isset($promo_id) || !isset($buyer_id)) {
            throw new BuyerCouponException('优惠券ID或者买家ID不能为空');
        }
        $coupon = BuyerCoupon::where(['promo_id' => $promo_id, 'buyer_id' => $buyer_id])->count();
        if ($coupon >= $promo['tickets_available']) {
            throw new BuyerCouponException('您领取次数已超出限制');
        }
        if ($promo['validity_type'] == Promo::VALIDITY_APPOINT_DATE) {
            $effect_time = $promo['effect_time'];
            $invalid_time = $promo['invalid_time'];
        } else {
            $effect_time = time();
            $invalid_time = strtotime('+' . $promo['days'] . 'days');
        }
        $data = array_merge($request->all(), ['effect_time' => $effect_time], ['invalid_time' => $invalid_time], ['status' => BuyerCoupon::STATUS_EFFECT]);

        $model = BuyerCoupon::create($data);
        return $model;
    }

    /**优惠券过期
     * @param int $promo_id
     * @param int $time
     * @return mixed
     * @throws BuyerCouponException
     */
    public function update(int $promo_id, int $time)
    {
        if ($promo_id) {
            $data = DB::transaction(function () use ($promo_id, $time) {
                $data = BuyerCoupon::where('promo_id', $promo_id)->update(['invalid_time' => $time, 'status' => BuyerCoupon::STATUS_INVALID]);
                return $data;
            }, 1);
        } else {
            throw new BuyerCouponException('优惠券ID不能为空');
        }
        return $data;
    }

    /**
     * @param int $promo_id
     * @return mixed
     */
    public function delete(int $promo_id)
    {
        if ($promo_id) {
            $data = BuyerCoupon::where('promo_id', $promo_id)->delete();
            return $data;
        }
    }

}