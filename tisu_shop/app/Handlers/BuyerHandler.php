<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/3/19
 * Time: 11:25
 */

namespace App\Handlers;


use App\Exceptions\BuyerException;
use App\Lib\Response\Result;
use App\Models\Buyer;
use App\Models\CustomerData;
use Illuminate\Http\Request;

class BuyerHandler
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(Request $request)
    {
        $query = Buyer::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['nick_name', 'shop_id'])) {
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

        $data = $query->paginate($per_page);
        return $data;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $fields = ['open_id', 'phone', 'union_id', 'nick_name', 'avatar_url', 'gender', 'remark', 'source', 'language', 'country', 'province', 'city', 'shop_id'];

        $data = $request->only($fields);
        ## 判断是否存储
        $buyer = Buyer::where([
            ['open_id', '=', $data['open_id']],
            ['shop_id', '=', $data['shop_id']],
        ])->first();

        if (!$buyer) {
            $buyer = Buyer::create($data);
        }

        return $buyer;

    }

    public function update(Request $request, int $id)
    {
//        $id = $request->get('id');
        $buyer = Buyer::find($id);
        if ($buyer) {
            $buyer->remark = $request->get('remark');
            $buyer->save();
        } else {
            throw new BuyerException('买家不存在');
        }

        return $buyer;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function register($data)
    {

        $fields = [
            'open_id', 'nick_name', 'avatar_url', 'gender', 'language', 'country', 'province', 'city', 'shop_id', 'appid'
        ];
        $buyerCreate = [];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $buyerCreate[$field] = $data[$field];
            }
        }

        ## 判断是否存储
        $buyer = Buyer::where([
            ['open_id', '=', $buyerCreate['open_id']],
            ['shop_id', '=', $buyerCreate['shop_id']],
        ])->first();

        if (!$buyer) {
            $buyer = Buyer::create($buyerCreate);
        }

        return $buyer;
    }

    /**
     * 确定买家省份
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function me(Request $request)
    {
        $query = Buyer::query();
        $query->where('open_id', $request->get('open_id'));
        $query->where('shop_id', $request->get('shop_id'));
        return $query->first();
    }

    public function create(array $param)
    {
        $model = CustomerData::insert($param);
        return $model;
    }
}