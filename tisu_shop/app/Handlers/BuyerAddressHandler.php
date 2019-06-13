<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/19
 * Time: 11:25
 */

namespace App\Handlers;


use App\Exceptions\BuyerAddressException;
use App\Lib\Response\Result;
use App\Models\BuyerAddress;
use Illuminate\Http\Request;

class BuyerAddressHandler
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = BuyerAddress::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {

                if (in_array($field, ['receiver', 'buyer_id', 'id', 'shop_id'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'receiver') !== false) {
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

        $data = $query->orderby('is_default', 'desc')->paginate($per_page);
        return $data;
    }

    /**
     * 修改默认状态
     * @param Request $request
     * @return mixed
     * @throws BuyerAddressException
     */
    public function change(Request $request)
    {
        $buyerAddress = BuyerAddress::find($request->get('id'));
        if (!$buyerAddress) {
            throw  new BuyerAddressException('收件地址不存在或者已删除', 100007);
        }

        \DB::transaction(function () use (&$buyerAddress, $request) {
            $is_default = $request->get('is_default');
            $this->defaultNot($buyerAddress->buyer_id, $buyerAddress->shop_id);
            $buyerAddress->is_default = $is_default;
            $buyerAddress->save();
        }, 1);

        return $buyerAddress;
    }

    /**
     * @param $buyer_id
     * @param $shop_id
     * @return mixed
     */
    public function defaultNot($buyer_id, $shop_id)
    {
        return BuyerAddress::where([
            ['buyer_id', '=', $buyer_id],
            ['shop_id', '=', $shop_id],
            ['is_default', '=', 1],
        ])->update(['is_default' => 0]);
    }

}