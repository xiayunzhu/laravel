<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\Response\Result;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * 店铺消息列表-按消息类型组装
     * @param ListRequest $request
     * @param Result $result
     */
    public function index(Request $request, Result $result)
    {
        //获取登录店铺的未读消息
        $select_shop = $request->get('shop_id');
        if (!$select_shop) {
            $result->failed('未选择店铺');
        } else {
            //返回对象
            $messages = Message::where('shop_id', $select_shop)->where('status', Message::STATUS_WAIT)->orderBy('created_at', 'desc')->get();
            //重新组装
            $res = array();
            foreach ($messages as $val) {
                if (isset($res[$val->type])) {
                    $res[$val->type]['count'] += 1;
                } else {
                    $res[$val->type] = $val;
                    $res[$val->type]['count'] = 1;
                }
            }

            $result->succeed($res);
        }

        return $result->toArray();
    }

    /**
     * 按类型查询消息
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        //获取登录店铺的未读消息
        $select_shop = $request->get('shop_id');
        $type = $request->get('type');
        if ($select_shop && $type) {
            //支持订单号查询
            $queryFields = array('type', 'shop_id', 'eid');
            $query = Message::query();
            foreach ($queryFields as $val) {
                if ($val == 'eid' && $request->has('eid')) {
                    $query->where('details', 'like', '%' . $request->get($val) . '%');

                } else if ($request->has($val)) {
                    $query->where($val, $request->get($val));

                }
            }

            //每页数量
            $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
            $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100

            $messages = $query->orderBy('status')->orderBy('created_at', 'desc')->paginate($per_page);
            if (in_array(strtoupper($type), array('ORDER', 'REFUND'))) {
                foreach ($messages as &$val) {
                    list($eid, $fee, $num) = explode('|', $val->details, 3);
                    $val->eid = $eid;
                    $val->fee = $fee;
                    $val->num = $num;
                }
            }

            $result->succeed($messages);

        } else {
            $result->failed('参数错误');
        }

        return $result->toArray();
    }


    /**
     * 更新消息状为已读
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function update(Request $request, Result $result)
    {
        //更新消息状为已读
        $select_shop = $request->get('shop_id');
        $type = strtoupper($request->get('type'));
        if ($type && $select_shop) {
            $messages = Message::where('shop_id', $select_shop)->where('type', $type)->update(['status' => Message::STATUS_DONE]);
            $result->succeed($messages);

        } else {
            $result->failed('参数错误');
        }

        return $result->toArray();
    }


}
