<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/4/22
 * Time: 16:11
 */

namespace App\Handlers;


use App\Models\Turnover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnoverHandler
{
    public function page(Request $request,int $begin_time,int $end_time)
    {
        $query = Turnover::query();

        //查询条件处理
        if ($queryFields = $request->all()) {
            foreach ($queryFields as $field => $value) {

                if (in_array($field, ['shop_id'])) {
                    if (!empty($value)) {
                            $query->where($field, $value);
                    }
                }
            }
        }
        //var_dump($begin_time);die;
        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->whereBetween('pay_time',[$begin_time,$end_time]);
        $data = $query->paginate($per_page)->toArray();
        $sum = $query->selectRaw('sum(payment) as payment')->first();
        $data['total']=$sum['payment'];
        return $data;
    }

}