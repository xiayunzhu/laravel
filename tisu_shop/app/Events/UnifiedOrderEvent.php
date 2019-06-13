<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UnifiedOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $unifiedOrderResult;

    /**
     * Create a new event instance.
     *
     * @param array $unifiedOrderResult
     */
    public function __construct(array $unifiedOrderResult)
    {
        //
        $this->unifiedOrderResult = $unifiedOrderResult;
    }

    /**
     * @return array
     */
    public function getUnifiedOrderResult(): array
    {
        return $this->unifiedOrderResult;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
