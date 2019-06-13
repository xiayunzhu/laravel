<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 11:49
 */

namespace App\Handlers;


use App\Exceptions\EventException;
use App\Lib\Response\Result;
use App\Models\Event;
use App\Models\EventItem;
use App\Models\Promo;
use Illuminate\Http\Request;

class EventHandler
{
    public function Page(Request $request)
    {
        $query = Event::query();

        //查询条件处理
        if ($queryFields = $request->only(Event::$query_fileds)) {
            foreach ($queryFields as $field => $value) {
                if (in_array($field, ['type'])) {
                    if (!empty($value)) {
                        if (strpos($field, 'title') !== false) {
                            $query->where($field, 'like', '%' . $value . '%');
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }
            }
        }
        ## 状态筛选
        $proceed_status = $request->get('status');
        $event_begin = $request->get('event_begin');
        $event_end = $request->get('event_end');
        if (isset($event_begin) && isset($event_end)) {
            $query->where([['event_end', '<=', $event_end], ['event_begin', '>=', $event_begin]]);
        }
        if (isset($proceed_status)) {
            switch ($proceed_status) {
                ## 未开始
                case Event::EVENT_STATUS_ENABLE:
                    $query->where([['event_begin', '>', time()]]);
                    break;
                ## 进行中
                case Event::EVENT_STATUS_ONGOING:
                    $query->where([['event_end', '>=', time()], ['event_begin', '<', time()]]);
                    break;
                ## 已过期
                case Promo::PROCEED_STATUS_EXPIRED:
                    $query->where([['event_end', '<', time()]]);
                    break;
                default:
                    break;
            }
        }

        //每页数量
        $per_page = $request->get('per_page') ? $request->get('per_page') : config('admin.paginate.limit');
        $per_page = $per_page > 100 ? 100 : intval($per_page);//限制最大100
        $query->select(['id', 'type', 'title', 'event_begin', 'event_end', 'status'])->orderBy('event_end', 'desc');
        $data = $query->paginate($per_page);
        if ($data) {
            $data = $data->toArray();
            foreach ($data['data'] as $key => $val) {
                if ($val['event_begin'] > time()) {
                    $data['data'][$key]['status'] = Event::EVENT_STATUS_ENABLE;
                } elseif ($val['event_begin'] < time() && $val['event_end'] > time()) {
                    $data['data'][$key]['status'] = Event::EVENT_STATUS_ONGOING;
                } elseif ($val['event_end'] < time()) {
                    $data['data'][$key]['status'] = Event::EVENT_STATUS_UNABLE;
                }
            }
        }
        return $data;

    }

    /**活动详情
     * @param Request $request
     * @return mixed
     * @throws EventException
     */
    public function detail(Request $request)
    {
        $event_id = $request->get('event_id');
        $event = Event::find($event_id);
        if (!$event) {
            throw new EventException('该活动不存在');
        } else {
            return $event;
        }
    }
}