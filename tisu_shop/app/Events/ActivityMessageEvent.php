<?php

namespace App\Events;

use App\Models\ShopEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActivityMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $shopEvent;
    private $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ShopEvent $shopEvent, string $type)
    {
        //
        $this->shopEvent = $shopEvent;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return ShopEvent
     */
    public function getShopEvent(): ShopEvent
    {
        return $this->shopEvent;
    }

    /**
     * @param ShopEvent $shopEvent
     */
    public function setShopEvent(ShopEvent $shopEvent)
    {
        $this->shopEvent = $shopEvent;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }


}
