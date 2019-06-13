<?php

namespace App\Listeners;

use App\Events\MessageEvent;
use App\Exceptions\InvalidRequestException;
use App\Handlers\MessageHandler;
use App\Models\Message;

class MessageEventListener
{
    private $messageHandler;

    /**
     * Create the event listener.
     *
     * @param MessageHandler $messageHandler
     */
    public function __construct(MessageHandler $messageHandler)
    {
        //
        $this->messageHandler = $messageHandler;
    }

    /**
     * Handle the event.
     *
     * @param MessageEvent $event
     * @return void
     * @throws InvalidRequestException
     */
    public function handle(MessageEvent $event)
    {
        //订单
        $order = $event->getOrder();
        $type = strtoupper($event->getType());
        $message = array();
        $message['type'] = $type;
        $message['status'] = Message::STATUS_WAIT;
        $message['shop_id'] = isset($order->shop_id) ? $order->shop_id : '';

        if ($type == Message::TYPE_ORDER) {
            $message['content'] = '有一笔新订单，尽快处理哦。';
        } elseif ($type == Message::TYPE_REFUND) {
            $message['content'] = '您的卖家发起退款，请您在七天内处理，预期系统将自动退款。';
        } else {
            throw new InvalidRequestException("订单类型错误");
        }

        $num = 0;
        foreach ($order->order_items as $item) {
            $num += $item->num;
        }

        $message['details'] = $order->order_no . '|' . $order->paid_fee . '|' . $num;

        $this->messageHandler->store($message);

    }
}
