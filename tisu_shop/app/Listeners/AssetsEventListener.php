<?php

namespace App\Listeners;

use App\Events\AssetsEvent;
use App\Handlers\AssetsHandler;
use App\Models\OrderItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssetsEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $assetsHandler;

    public function __construct(AssetsHandler $assetsHandler)
    {
        $this->assetsHandler = $assetsHandler;
        //
    }

    /**
     * @param AssetsEvent $event
     */
    public function handle(AssetsEvent $event)
    {
        //
        $order = $event->getOrder();
        $type = strtoupper($event->getType());
        $turnover = array();
        $items = OrderItem::where('order_id', $order->id)->get()->toArray();
        foreach ($items as $item) {
            $turnover['shop_id'] = $order->shop_id;
            $turnover['order_id'] = $order->id;
            $turnover['order_no'] = $order->order_no;
            $turnover['goods_id'] = $item['goods_id'];
            $turnover['goods_name'] = $item['goods_name'];
            $turnover['num'] = $item['num'];
            $turnover['discount_fee'] = 0;
            if ($order->discount_fee > 0 && $order->total_fee > 0) {

                $turnover['discount_fee'] = ($item['payment'] / $order->total_fee) * $order->discount_fee;
            }
            $turnover['payment'] = $item['payment'] - $turnover['discount_fee'];
            $turnover['type'] = $type;
            $turnover['pay_time'] = $order->pay_time;
            $this->assetsHandler->store($turnover);
        }
    }
}
