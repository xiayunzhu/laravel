<?php

namespace App\Listeners;

use App\Events\ActivityMessageEvent;
use App\Exceptions\InvalidRequestException;
use App\Handlers\MessageHandler;
use App\Models\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityMessageEventListener
{
    private $messageHandler;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MessageHandler $messageHandler)
    {
        //
        $this->messageHandler = $messageHandler;
    }

    /**
     * Handle the event.
     *
     * @param ActivityMessageEvent $event
     * @return void
     */
    public function handle(ActivityMessageEvent $event)
    {
        //
        $shopEvent = $event->getShopEvent();
        $type = strtoupper($event->getType());

        $message = array();

        if ($type == Message::EVENT_STATUS_ENABLE) {
            $message['content'] = '您报名的活动已通过审核。';
        } else {
            throw new InvalidRequestException("通知类型错误");
        }

        $message['type'] = $type;
        $message['status'] = Message::STATUS_WAIT;
        $message['shop_id'] = $shopEvent->shop_id;
        $message['details'] = $shopEvent->event_id;

        $this->messageHandler->store($message);
    }
}
