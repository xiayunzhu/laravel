<?php
/**
 * Created by PhpStorm.
 * User: ML-06
 * Date: 2019/5/11
 * Time: 12:30
 */

namespace App\Handlers;


use App\Exceptions\ShopEventException;
use App\Models\Event;
use App\Models\EventItem;
use App\Models\Promo;
use App\Models\PromoItem;
use App\Models\PromoShop;
use App\Models\ShopEvent;
use Illuminate\Http\Request;

class ShopEventHandler
{
    private $promoShopHandler;

    /**
     * ShopEventHandler constructor.
     * @param PromoShopHandler $promoShopHandler
     */
    public function __construct(PromoShopHandler $promoShopHandler)
    {
        $this->promoShopHandler = $promoShopHandler;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ShopEventException
     */
    public function store(Request $request)
    {
        $event_id = $request->get('event_id');
        $shop_id = $request->get('shop_id');
        $event = Event::where([['status', '=', Event::EVENT_STATUS_ONGOING], ['id', '=', $event_id]])->first();
        if (!$event || $event->event_begin > time() || $event->event_end < time()) {
            throw new ShopEventException('该活动未开始或者已过期');
        }
        $event_tmp = ShopEvent::where([['shop_id', '=', $shop_id], ['event_id', '=', $event_id]])->value('id');
        if ($event_tmp) {
            throw new ShopEventException('您已报名');
        }
        $promo_ids = EventItem::where('event_id', $event_id)->get()->pluck(['promo_id'])->toArray();
        $count = 0;
        $model = \DB::transaction(function () use ($event_id, $shop_id, $request, $promo_ids, $count) {
            if (count($promo_ids) > 0) {
                $promo_list = Promo::whereIn('id', $promo_ids)->get(['id', 'range', 'type', 'total_count', 'apply_shop', 'status']);
                foreach ($promo_list as $key => $val) {
                    if ($val['apply_shop'] = Promo::SHOP_RANGE_PART_CAN) {
                        $tmp = PromoShop::where([['promo_id', '=', $val['id']], ['shop_id', '=', $shop_id]])->value('id');
                        if (!$tmp) {
                            continue;
                        }
                    } elseif ($val['apply_shop'] = Promo::SHOP_RANGE_PART_CANT) {
                        $tmp = PromoShop::where(['promo_id', '=', $val['id'], ['shop_id', '=', $shop_id]])->value('id');
                        if ($tmp) {
                            continue;
                        }
                    }
                    $count++;
                    $this->promoShopHandler->store($val, $request);
                }

            }
            if ($count > 0) {
                $data['shop_id'] = $shop_id;
                $data['event_id'] = $event_id;
                $data['status'] = ShopEvent::STATUS_ENABLE;
                ShopEvent::create($data);
                return true;
            } else {
                throw new ShopEventException('您不符合该活动条件');
            }
        }, 1);
        return $model;
    }

}